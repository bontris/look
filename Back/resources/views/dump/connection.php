<?php
  /**
   * Linoll Moreno Mosquera <linoll@bontris.com>
   *
   * © 2015 Bontris, S.A.S
   */

  class Connection {
  	/** engine types */

  	const POSTGRES = 0x02;

    const SQLITE = 0x02;

  	const MYSQL = 0x01;

  	/** statement types */

    const DELETE = 0x04;

    const CHANGE = 0x03;

    const CREATE = 0x02;

    const SELECT = 0x01;

    /** operator types */

    const BETWEEN = 0x800;

    const GREATER = 0x400;

    const EXISTS = 0x200;

    const EQUAL = 0x100;

    const START = 0x80;

    const LIKE = 0x40;

    const LESS = 0x20;

    const END = 0x10;

    const NOT = 0x08;

    const IN = 0x04;

    const IS = 0x02;

  	/** join types */

  	const OUTTER = 0x04;

  	const INNER = 0x03;

  	const RIGHT = 0x02;

  	const LEFT = 0x01;

    /** data types */

    const BOOLEAN = 0x01;

    const INTEGER = 0x02;

    const DECIMAL = 0x03;

    const STRING = 0x04;

    const DATE = 0x05;

    const TIME = 0x06;

    private $schemas = [];

    private $results = [];

    private $queries = [];

    private $havings = [];

    private $values = [];

  	private $fields = [];

    private $brings = [];

    private $meta = [];

    private $from = [];

  	private $join = [];

    private $load = [];

    private $last = [];

    private $fill = [];

    private $test = [];

    private $sort = [];

    private $rank = [];

    private $handle = null;

    private $engine = null;

    private $prefix = null;

    private $result = null;

    private $field = null;

    private $table = null;

    private $host = null;

    private $port = null;

    private $user = null;

    private $pass = null;

    private $link = null;

    private $have = null;

    private $lock = false;

    private $take = 0;

    private $skip = 0;

    private $time = 0;

  	public function __construct ($prefix, $host, $port, $user, $pass) {
      $this->prefix = strval($prefix);

  		$this->host = gethostbyname(strval($host));

  		$this->port = intval($port);

  		$this->user = strval($user);

  		$this->pass = strval($pass);
  	}

    public function __destruct() {
      $this->close(true);
    }

    public static function make ($prefix, $host, $port, $user, $pass) {
      return (new self($prefix, $host, $port, $user, $pass));
    }

  	public function open ($name, $engine, &$message = null, $charset = null) {
      if (empty($this->link)) {
        switch (($this->engine = intval($engine))) {
          case self::POSTGRES:
            if (($this->link = @pg_connect(sprintf('host=%s port=%d user=%s password=%s dbname=%s', $this->host,
                                                                                                    $this->port,
                                                                                                    $this->user,
                                                                                                    $this->pass,
                                                                                                    $name)))) {
              if (isset($charset)) {
                if ((@pg_set_client_encoding($this->link, strval($charset)) === 0)) {
                  return true;
                }

                return false;
              }

              return true;
            }
            break;
          case self::SQLITE:
            if (($this->link = @sqlite_open($name, 0666, $message))) {
              return true;
            }
            break;
          case self::MYSQL:
            if (($this->link = @mysqli_connect($this->host, $this->user, $this->pass, $name, $this->port))) {
              if (isset($charset)) {
                return @mysqli_set_charset($this->link, strval($charset));
              }

              return true;
            } else {
              $message = mysqli_connect_error();
            }
            break;
        }
      }

  		return ($this->link ? true : false);
  	}

    public function listen ($handle) {
      if (is_callable($handle)) {
        $this->handle = $handle;
      }

      return $this;
    }

    public function close ($free = true) {
      if ((gettype($this->link) === 'object')) {
        switch ($this->engine) {
          case self::POSTGRES:
            if (@pg_close($this->link)) {
              if ($free) {
                if (isset($this->result)) {
                  @pg_free_result($this->result);
                }

                foreach ($this->results as $result) {
                  @pg_free_result($result);
                }
              }

              $this->link = null;

              return true;
            }
            break;
          case self::SQLITE:
            @sqlite_close($this->link);

            if ($free) {
              if (isset($this->result)) {

              }

              foreach ($this->results as $result) {

              }
            }

            return true;
          case self::MYSQL:
            if (@mysqli_close($this->link)) {
              if ($free) {
                if (isset($this->result)) {
                  @mysqli_free_result($this->result);
                }

                foreach ($this->results as $result) {
                  @mysqli_free_result($result);
                }
              }

              $this->link = null;

              return true;
            }
            break;
        }
      }

      return false;
    }

    public function free ($cache = null) {
      if (isset($this->results[($cache = trim(strval($cache)))])) {
        $result = $this->results[$cache];

        unset($this->results[$cache]);
      }

      switch ($this->engine) {
        case self::POSTGRES:
          if (isset($result)) {
            @pg_free_result($result);
          } else {
            if (isset($this->result)) {
              @pg_free_result($this->result);

              $this->result = null;
            }
          }
          break;
        case self::SQLITE:
          if (isset($result)) {

          } else {
            if (isset($this->result)) {
              $this->result = null;
            }
          }
          break;
        case self::MYSQL:
          if (isset($result)) {
            @mysqli_free_result($result);
          } else {
            if (isset($this->result)) {
              @mysqli_free_result($this->result);

              $this->result = null;
            }
          }
          break;
      }

      return $this;
    }

    public function query ($text, &$fail = null, array $data = []) {
      if (($text = $this->prepare(trim(strval($text)), $data))) {
        if (isset($this->link)) {
          $start = microtime(true);

          switch ($this->engine) {
            case self::POSTGRES:
              if (($result = @pg_query($this->link, $text))) {
                if (is_resource($result)) {
                  if (is_resource($this->result)) {
                    @pg_free_result($this->result);
                  }

                  $this->result = $result;
                }

                if ($this->handle) {
                  @call_user_func($this->handle, round((microtime(true) - $start) * 1000, 2), $text, null);
                }

                return ($result ? true : false);
              } else {
                $fail = @pg_last_error($this->link);

                if ($this->handle) {
                  @call_user_func($this->handle, round((microtime(true) - $start) * 1000, 2), $text, $fail);
                }
              }
              break;
            case self::SQLITE:
              if (($result = @sqlite_query($this->link, $text, SQLITE_BOTH, $fail))) {
                if (is_resource($result)) {
                  $this->result = $result;
                }

                if ($this->handle) {
                  @call_user_func($this->handle, round((microtime(true) - $start) * 1000, 2), $text, null);
                }

                return ($result ? true : false);
              } else {
                if ($this->handle) {
                  @call_user_func($this->handle, round((microtime(true) - $start) * 1000, 2), $text, $fail);
                }
              }
              break;
            case self::MYSQL:
              if (($result = @mysqli_query($this->link, $text))) {
                if (is_object($result)) {
                  if (is_object($this->result)) {
                    @mysqli_free_result($this->result);
                  }

                  $this->result = $result;
                }

                if ($this->handle) {
                  @call_user_func($this->handle, round((microtime(true) - $start) * 1000, 2), $text, null);
                }

                return ($result ? true : false);
              } else {
                $fail = @mysqli_error($this->link);

                if ($this->handle) {
                  @call_user_func($this->handle, round((microtime(true) - $start) * 1000, 2), $text, $fail);
                }
              }
              break;
          }
        }
      }

      return false;
    }

    private function test ($name, $data, $sign, $flip) {
      $match = [];

      $test = [];

      if (($sign & self::IN)) {

      } else {
        if ($flip) {
          $test[] = 'NOT';
        }

        $test[] = $name;

        if (($sign & self::LIKE)) {
          $test[] = 'LIKE';

          $test[] = sprintf("'%%%s%%'", str_replace('%', '\\%', $this->cast($data, false)));
        } else {
          if (($sign & self::LESS)) {
            $test[] = '<';
          } else {
            if (($sign & self::EQUAL)) {
              if (($sign & self::GREATER)) {
                $test[] = '>=';
              } else {
                if (($sign & self::LESS)) {
                  $test[] = '<=';
                } else {
                  if (is_null($data)) {
                    $test[] = 'IS';
                  } else {
                    $test[] = '=';
                  }
                }
              }
            } else {
              if (($sign & self::GREATER)) {
                $test[] = '>';
              } else {
                if (($sign & self::BETWEEN)) {
                  $test[] = 'BETWEEN';

                  $test[] = $this->cast($data[0], true);

                  $test[] = 'AND';

                  $test[] = $this->cast($data[1], true);

                  return implode(' ', $test);
                } else {
                  if (is_null($data)) {
                    $test[] = 'IS';
                  } else {
                    $test[] = '=';
                  }
                }
              }
            }
          }

          $test[] = $this->cast($data, true);
        }
      }

      return implode(' ', $test);
    }

    private function escape ($string) {
      switch ($this->engine) {
        case self::POSTGRES:
          return @pg_escape_string($this->link, stripslashes(trim($string)));
        case self::SQLITE:
          return @sqlite_escape_string(stripslashes(trim($string)));
        case self::MYSQL:
          return @mysqli_real_escape_string($this->link, stripslashes(trim($string)));
      }

      return stripslashes(trim($string));
    }

    private function dumb ($meta, $list) {
      if (isset($list)) {
        $main = true;

        $item = null;

        $hash = [];

        foreach ($meta as $from => $data) {
          foreach ($data as $name => $data) {
            switch ($data['type']) {
              case self::BOOLEAN:
                $item = boolval($list[$data['seek']]);
                break;
              case self::INTEGER:
                $item = intval($list[$data['seek']]);
                break;
              case self::DECIMAL:
                $item = floatval($list[$data['seek']]);
                break;
              case self::STRING:
                $item = strval($list[$data['seek']]);
                break;
              default:
                $item = $list[$data['seek']];
            }

            if (($main || $data['main'])) {
              $hash[$name] = $item;
            } else {
              $hash[$from][$name] = $item;
            }
          }

          $main = false;
        }

        return $hash;
      }
      
      return false;
    }

    private function cast ($value, $quote = true) {
      $matches = [];

      switch (gettype($value)) {
        case 'boolean':
          return ($value ? '1' : '0');
        case 'integer':
          return strval($value);
        case 'string':
          if (preg_match('/^(?P<table>\w+(_\w+)*)\.(?P<field>\w+(_\w+)*)$/i', $value, $matches)) {
            return sprintf('%s.%s', $this->quote($matches['table'], true), $this->quote($matches['field'], true));
          }

          if (preg_match('/^[a-z]([a-z0-9])*[.][a-z]([a-z0-9])*$/i', $value)) {
            return $value;
          }

          if (preg_match('/^\{([a-z]([a-z0-9])*([:|.][a-z]([a-z0-9])*)?)(%([b|d|t|i|f|s]))?\}$/i', $value)) {
            return $value;
          }

          if (preg_match('/^[a-z]+(_?[a-z0-9]+)*\(.+\)$/i', $value)) {
            return $value;
          }

          $value = $this->escape($value);

          if ($quote) {
            return $this->quote($value);
          }

          return $value;
        case 'object':
          $buffer = [];

          foreach (get_object_vars($value) as $name) {
            $buffer[] = $this->cast($value->$name, $quote);
          }

          return implode(', ', $buffer);
        case 'double':
          return strval($value);
        case 'float':
          return strval($value);
        case 'array':
          $buffer = [];

          foreach ($value as $item) {
            $buffer[] = $this->cast($item, $quote);
          }

          return implode(', ', $buffer);
      }

      return 'NULL';
    }

    private function quote ($text, $name = false) {
      switch ($this->engine) {
        case self::POSTGRES:
          if ($name) {
            return ('"' . $text . '"');
          } else {
            return ("'" . $text . "'");
          }
        case self::SQLITE:
          if ($name) {
            return ('"' . $text . '"');
          } else {
            return ("'" . $text . "'");
          }
        case self::MYSQL:
          if ($name) {
            return ('`' . $text . '`');
          } else {
            return ("'" . $text . "'");
          }
      }
    }

    /** utility functions */

    private function prepare ($text, array $data) {
      return preg_replace_callback('/\{(?<item>\w+([:|.]\w+)?)(%(?<type>[b|d|t|i|f|s]))?\}/i', function ($match) use ($data) {
        if (isset($data[$match['item']])) {
          switch ((isset($match['type']) ? strtolower($match['type']) : false)) {
            case 'b':
              return ($data[$match['item']] ? '1' : '0');
            case 'i':
              return strval(intval($data[$match['item']]));
            case 'f':
              return strval(floatval($data[$match['item']]));
            case 'd':
              return $this->quote(date('Y-m-d H:i:s', is_string($data[$match['item']]) ? @strtotime($data[$match['item']]) :
                                                                                         intval($data[$match['item']])));
            case 't':
              return $this->quote(date('H:i:s', is_string($data[$match['item']]) ? @strtotime($data[$match['item']]) :
                                                                                   intval($data[$match['item']])));
            case 's':
              return $this->quote($this->escape(strval($data[$match['item']])));
            default:
              if (is_string($data[$match['item']])) {
                return $this->quote($this->escape($data[$match['item']]));
              } else {
                if (is_numeric($data[$match['item']])) {
                  return strval($data[$match['item']]);
                } else {
                  return 'NULL';
                }
              }
          }
        }

        return $match[0];
      }, $text);
    }

    public function bind ($name, $data, $type = 0x00) {
      if (preg_match('/^[a-z]([a-z0-9])*([:|.][a-z]([a-z0-9])*)?$/i', ($name = trim(strval($name))))) {
        switch ($type) {
          case self::BOOLEAN:
            $this->values[$name] = boolval($data);
            break;
          case self::INTEGER:
            $this->values[$name] = intval($data);
            break;
          case self::DECIMAL:
            $this->values[$name] = floatval($data);
            break;
          default:
            $this->values[$name] = strval($data);
            break;
        }
      }

      return $this;
    }

    public function cache ($name) {
      if (preg_match('/^[a-z]([a-z0-9])*([:|.][a-z]([a-z0-9])*)?$/i', ($name = trim(strval($name))))) {
        if (isset($this->result)) {
          $this->results[$name] = $this->result;
        }
      }

      return $this;
    }

    /** statement functions */

    public function delete ($ignore, &$rows = null, &$fail = null) {
      if (count($this->from)) {
        foreach ($this->from as $name => $data) {
          $join = false;

          $nest = false;

          $main = [];

          $test = [];

          $take = [];

          $main[] = 'DELETE';

          if ($ignore) {
            switch ($this->engine) {
              case self::SQLITE:
                $main[] = 'IGNORE';
                break;
              case self::MYSQL:
                $main[] = 'IGNORE';
                break;
            }
          }

          $main[] = 'FROM';

          $main[] = $this->quote($data['name'], true);

          if (isset($this->test[$name])) {
            foreach ($this->test[$name] as $data) {
              if (empty($test)) {
                $test[] = 'WHERE';
              }

              if ($nest) {
                if ($data['nest']) {
                  $test[] = ')';
                }
              }

              if ($join) {
                if ($data['join']) {
                  $test[] = 'AND';
                } else {
                  $test[] = 'OR';
                }
              }

              if ($data['nest']) {
                $test[] = '(';

                $nest = true;
              }

              $test[] = $this->test($data['raw'] ? $data['name'] : $this->quote($data['name'], true), $data['data'], $data['sign'], $data['flip']);

              $join = true;
            }

            if ($nest) {
              $test[] = ')';
            }
          }

          if ($this->take) {
            $take[] = 'LIMIT';

            $take[] = $this->take;
          }

          if ($this->query(implode(' ', array_merge($main, $test, $take)), $fail, $this->values)) {
            switch ($this->engine) {
              case self::POSTGRES;
                $rows = @pg_affected_rows($this->result);
                break;
              case self::SQLITE:
                $rows = @sqlite_changes($this->link);
                break;
              case self::MYSQL:
                $rows = @mysqli_affected_rows($this->link);
                break;
            }

            $this->from = [];

            $this->fill = [];

            $this->test = [];

            $this->join = [];

            $this->rank = [];

            $this->sort = [];

            $this->load = [];

            $this->last = [];

            $this->skip = 0;

            $this->take = 0;

            return true;
          }
        }
      }
          
      return false;
    }

    public function change ($ignore, &$rows = null, &$fail = null) {
      if (count($this->fill)) {
        foreach ($this->fill as $from => $list) {
          $join = false;

          $nest = false;

          $main = [];

          $test = [];

          $take = [];

          $data = [];

          $item = 0;

          $main[] = 'UPDATE';

          if ($ignore) {
            switch ($this->engine) {
              case self::SQLITE:
                $main[] = 'IGNORE';
                break;
              case self::MYSQL:
                $main[] = 'IGNORE';
                break;
            }
          }

          $main[] = $this->quote($this->from[$from]['name'], true);

          $main[] = 'SET';

          foreach ($list as $hash) {
            $data[] = $hash['data'];

            if ($item) {
              $main[] = ',';
            }

            $main[] = $this->quote($hash['name'], true);

            $main[] = '=';

            switch ($hash['type']) {
              case self::BOOLEAN:
                $main[] = sprintf('{%d%%b}', $item++);
                break;
              case self::INTEGER:
                $main[] = sprintf('{%d%%i}', $item++);
                break;
              case self::DECIMAL:
                $main[] = sprintf('{%d%%f}', $item++);
                break;
              case self::STRING:
                $main[] = sprintf('{%d%%s}', $item++);
                break;
              case self::DATE:
                $main[] = sprintf('{%d%%d}', $item++);
                break;
              case self::TIME:
                $main[] = sprintf('{%d%%t}', $item++);
                break;
              default:
                switch (gettype($hash['data'])) {
                  case 'boolean':
                    $main[] = sprintf('{%d%%b}', $item++);
                    break;
                  case 'integer':
                    $main[] = sprintf('{%d%%i}', $item++);
                    break;
                  case 'double':
                    $main[] = sprintf('{%d%%f}', $item++);
                    break;
                  case 'string':
                    $main[] = sprintf('{%d%%s}', $item++);
                    break;
                  default:
                    $main[] = 'NULL';

                    $item++;
                }
            }
          }

          if (isset($this->test[$from])) {
            foreach ($this->test[$from] as $hash) {
              if (empty($test)) {
                $test[] = 'WHERE';
              }

              if ($nest) {
                if ($hash['nest']) {
                  $test[] = ')';
                }
              }

              if ($join) {
                if ($hash['join']) {
                  $test[] = 'AND';
                } else {
                  $test[] = 'OR';
                }
              }

              if ($hash['nest']) {
                $test[] = '(';

                $nest = true;
              }

              $test[] = $this->test($hash['raw'] ? $hash['name'] : sprintf('%s.%s', $this->quote($from, true), $this->quote($hash['name'], true)),
                                    $hash['data'],
                                    $hash['sign'],
                                    $hash['flip']);

              $join = true;
            }

            if ($nest) {
              $test[] = ')';
            }
          }

          if ($this->take) {
            $take[] = 'LIMIT';

            $take[] = $this->take;
          }

          if ($this->query(implode(' ', array_merge($main, $test, $take)), $fail, array_merge($this->values, $data))) {
            switch ($this->engine) {
              case self::POSTGRES:
                if ((@pg_result_status($this->result, PGSQL_STATUS_LONG) === PGSQL_COMMAND_OK)) {
                  $rows = @pg_affected_rows($this->result);
                }
                break;
              case self::SQLITE:
                $rows = @sqlite_changes($this->link);
                break;
              case self::MYSQL:
                $rows = @mysqli_affected_rows($this->link);
                break;
            }

            $this->from = [];

            $this->fill = [];

            $this->test = [];

            $this->join = [];

            $this->rank = [];

            $this->sort = [];

            $this->load = [];

            $this->last = [];

            $this->skip = 0;

            $this->take = 0;

            return true;
          }
        }
      }

      return false;
    }

    public function create ($ignore, &$last = null, &$fail = null) {
      if (count($this->fill)) {
        foreach ($this->fill as $from => $list) {
          $pieces = [];

          $fields = [];

          $values = [];

          $data = [];

          foreach ($list as $item) {
            $fields[] = $this->quote($item['name'], true);

            $data[] = $item['data'];

            switch ($item['type']) {
              case self::BOOLEAN:
                $values[] = sprintf('{%d%%b}', count($values));
                break;
              case self::INTEGER:
                $values[] = sprintf('{%d%%i}', count($values));
                break;
              case self::DECIMAL:
                $values[] = sprintf('{%d%%f}', count($values));
                break;
              case self::STRING:
                $values[] = sprintf('{%d%%s}', count($values));
                break;
              case self::DATE:
                $values[] = sprintf('{%d%%d}', count($values));
                break;
              case self::TIME:
                $values[] = sprintf('{%d%%t}', count($values));
                break;
              default:
                switch (gettype($item['data'])) {
                  case 'boolean':
                    $values[] = sprintf('{%d%%b}', count($values));
                    break;
                  case 'integer':
                    $values[] = sprintf('{%d%%i}', count($values));
                    break;
                  case 'double':
                    $values[] = sprintf('{%d%%f}', count($values));
                    break;
                  case 'string':
                    $values[] = sprintf('{%d%%s}', count($values));
                    break;
                  default:
                    $values[] = 'NULL';
                }
            }
          }

          $pieces[] = 'INSERT';

          if ($ignore) {
            switch ($this->engine) {
              case self::MYSQL:
                $pieces[] = 'IGNORE ';
                break;
            }
          }

          $pieces[] = 'INTO';

          $pieces[] = $this->quote($this->from[$from]['name'], true);

          $pieces[] = '(';

          $pieces[] = implode(',', $fields);

          $pieces[] = ')';

          $pieces[] = 'VALUES';

          $pieces[] = '(';

          $pieces[] = implode(',', $values);

          $pieces[] = ')';

          if ($this->query(implode(' ', $pieces), $fail, array_merge($this->values, $data))) {
            switch ($this->engine) {
              case self::POSTGRES;
                if ((@pg_result_status($this->result, PGSQL_STATUS_LONG) === PGSQL_COMMAND_OK)) {
                  $last = @pg_last_oid($this->result);
                }
                break;
              case self::SQLITE:
                $last = @sqlite_last_insert_rowid($this->link);
                break;
              case self::MYSQL:
                $last = @mysqli_insert_id($this->link);
                break;
            }

            $this->from = [];

            $this->fill = [];

            $this->join = [];

            $this->rank = [];

            $this->sort = [];

            $this->load = [];

            $this->last = [];

            $this->skip = 0;

            $this->take = 0;

            return true;
          }
        }
      }

      return false;
    }

    public function select ($once, $keep, &$rows = null, &$fail = null) {
      if (count($this->load)) {
        $seek = 0;

        $main = [];

        $test = [];

        $sort = [];

        $rank = [];

        $take = [];

        $this->meta = [];

        foreach ($this->load as $from => $list) {
          foreach ($list as $name => $data) {
            if (count($main)) {
              $main[] = ',';
            } else {
              $main[] = 'SELECT';

              if ($once) {
                $main[] = 'DISTINCT';
              }
            }

            if (isset($data['name'])) {
              if (trim($data['list'])) {
                $list = [];

                foreach (preg_split('/,/', trim($data['list'])) as $item) {
                    if (preg_match('/^([A-Z]+)$/', trim($item))) {
                      array_push($list, trim($item));
                    } else {
                      if (preg_match('/^(\w+(_\w+)*)$/i', $item)) {
                        array_push($list, sprintf('%s.%s', $this->quote($from, true), $this->quote(trim($item), true)));
                      } else {
                        array_push($list, trim($item));
                      }
                    }
                }

                array_push($main, sprintf('%s(%s)', strtoupper($data['name']), implode(', ', $list)));
              }
            } else {
              array_push($main, sprintf('%s.%s', $this->quote($from, true), $this->quote($name, true)));
            }

            $this->meta[$from][isset($data['nick']) ? $data['nick'] : $name] = ['seek' => $seek++, 'type' => $data['type'], 'main' => $data['main']];

            array_push($main, sprintf('AS %s', $this->quote($seek, true)));
          }
        }

        $main[] = 'FROM';

        $push = false;

        $join = false;

        $nest = 0;

        foreach ($this->from as $name => $data) {
          if ($push) {
            $main[] = ',';
          }

          $main[] = $this->quote($data['name'], true);

          if ($data['nick']) {
            $main[] = sprintf('AS %s', $this->quote($data['nick'], true));
          }

          if (isset($this->test[$name])) {
            foreach ($this->test[$name] as $data) {
              if (empty($test)) {
                $test[] = 'WHERE';
              }

              if ($join) {
                if ($data['join']) {
                  if ($data['nest']) {
                    while ($nest) {
                      $test[] = ')';

                      $nest--;
                    }
                  }
                  
                  $test[] = 'AND';
                } else {
                  $test[] = 'OR';
                }
              }

              if ($data['nest']) {
                $test[] = '(';

                $nest++;
              }
              
              if (isset($data['list'])) {
                $list = [];

                foreach (preg_split('/,/', trim($data['list'])) as $item) {
                  if (preg_match('/^(\w+(_\w+)*)$/i', $item)) {
                    array_push($list, sprintf('%s.%s', $this->quote($name, true), $this->quote(trim($item), true)));
                  } else {
                    array_push($list, trim($item));
                  }
                }

                array_push($test, $this->test(sprintf('%s(%s)', strtoupper($data['name']), implode(', ', $list)), $data['data'], $data['sign'], $data['flip']));
              } else {
                array_push($test, $this->test(sprintf('%s.%s', $this->quote($name, true), $this->quote($data['name'], true)), $data['data'], $data['sign'], $data['flip']));
              }

              $join = true;
            }

            while ($nest) {
              $test[] = ')';

              $nest--;
            }
          }

          if (isset($this->sort[$name])) {
            foreach ($this->sort[$name] as $item) {
              if (empty($sort)) {
                $sort[] = 'GROUP BY';
              } else {
                $sort[] = ',';
              }
              
              if (isset($item['list'])) {
                $list = [];

                foreach (preg_split('/,/', trim($item['list'])) as $data) {
                  if (preg_match('/^([A-Z]+)$/', trim($data))) {
                    array_push($list, trim($data));
                  } else {
                    if (preg_match('/^(\w+(_\w+)*)$/i', trim($data))) {
                      array_push($list, sprintf('%s.%s', $this->quote($name, true), $this->quote(trim($data), true)));
                    } else {
                      array_push($list, trim($data));
                    }
                  }
                }
                
                $sort[] = sprintf('%s(%s)', strtoupper($item['name']), implode(', ', $list));
              } else {
                $sort[] = sprintf('%s.%s', $this->quote($name, true), $this->quote($item, true));
              }
            }
          }

          if (isset($this->rank[$name])) {
            foreach ($this->rank[$name] as $data) {
              if (empty($rank)) {
                $rank[] = 'ORDER BY';
              } else {
                $rank[] = ',';
              }

              if (isset($this->meta[$name][$data['name']])) {
                $rank[] = $this->quote($this->meta[$name][$data['name']]['seek'] + 1, true);
              } else {
                $rank[] = sprintf('%s.%s', $this->quote($name, true), $this->quote($data['name'], true));
              }

              if ($data['down']) {
                $rank[] = 'DESC';
              } else {
                $rank[] = 'ASC';
              }
            }
          }

          $push = true;
        }

        if (count($this->join)) {
          foreach ($this->join as $data) {
            $from = $data['name'];

            $join = false;

            $nest = false;

            switch ($data['kind']) {
              case self::OUTTER:
                $main[] = 'OUTTER';
                break;
              case self::INNER:
                $main[] = 'INNER';
                break;
              case self::RIGHT:
                $main[] = 'RIGHT';
                break;
              case self::LEFT:
                $main[] = 'LEFT';
                break;
            }

            $main[] = 'JOIN';

            $main[] = $this->quote($from, true);

            if ($data['nick']) {
              $main[] = sprintf('AS %s', $this->quote(($from = $data['nick']), true));
            }

            if (isset($this->test[$from])) {
              $main[] = 'ON';

              foreach ($this->test[$from] as $data) {
                if ($nest) {
                  if ($data['nest']) {
                    $main[] = ')';
                  }
                }

                if ($join) {
                  if ($data['join']) {
                    $main[] = 'AND';
                  } else {
                    $main[] = 'OR';
                  }
                }

                if ($data['nest']) {
                  $main[] = '(';

                  $nest = true;
                }

                if (isset($data['list'])) {
                  $list = [];

                  foreach (preg_split('/,/', trim($data['list'])) as $item) {
                    if (preg_match('/^(\w+(_\w+)*)$/i', $item)) {
                      array_push($list, sprintf('%s.%s', $this->quote($name, true), $this->quote(trim($item), true)));
                    } else {
                      array_push($list, trim($item));
                    }
                  }

                  array_push($main, $this->test(sprintf('%s(%s)', strtoupper($data['name']), implode(', ', $list)), $data['data'], $data['sign'], $data['flip']));
                } else {
                  array_push($main, $this->test(sprintf('%s.%s', $this->quote($from, true), $this->quote($data['name'], true)), $data['data'], $data['sign'], $data['flip']));
                }

                $join = true;
              }

              if ($nest) {
                $main[] = ')';
              }
            }

            if (isset($this->sort[$from])) {
                foreach ($this->sort[$from] as $item) {
                  if (empty($sort)) {
                    $sort[] = 'GROUP BY';
                  } else {
                    $sort[] = ',';
                  }
                  
                  if (isset($item['list'])) {
                    $list = [];

                    foreach (preg_split('/,/', trim($item['list'])) as $data) {
                      if (preg_match('/^([A-Z]+)$/', trim($data))) {
                        array_push($list, trim($data));
                      } else {
                        if (preg_match('/^(\w+(_\w+)*)$/i', trim($data))) {
                          array_push($list, sprintf('%s.%s', $this->quote($from, true), $this->quote(trim($data), true)));
                        } else {
                          array_push($list, trim($data));
                        }
                      }
                    }
                
                    $sort[] = sprintf('%s(%s)', strtoupper($item['name']), implode(', ', $list));
                  } else {
                    $sort[] = sprintf('%s.%s', $this->quote($from, true), $this->quote($item, true));
                  }
                }
            }

            if (isset($this->rank[$from])) {
              foreach ($this->rank[$from] as $data) {
                if (empty($rank)) {
                  $rank[] = 'ORDER BY';
                } else {
                  $rank[] = ',';
                }

                if (isset($this->meta[$from][$data['name']])) {
                  $rank[] = $this->quote($this->meta[$from][$data['name']]['seek'] + 1, true);
                } else {
                  $rank[] = sprintf('%s.%s', $this->quote($from, true), $this->quote($data['name'], true));
                }

                if ($data['down']) {
                  $rank[] = 'DESC';
                } else {
                  $rank[] = 'ASC';
                }
              }
            }
          }
        }

        if (($this->engine === self::POSTGRES)) {
            if ($this->take) {
              $take[] = 'LIMIT';

              $take[] = $this->take;
            }

            if ($this->skip) {
              $take[] = 'OFFSET';

              $take[] = $this->skip;
            }
          } else {
            if (($this->take || $this->skip)) {
              $take[] = 'LIMIT';

              $take[] = $this->skip;

              $take[] = ',';

              $take[] = $this->take;
            }
          }

        if ($this->query(implode(' ', array_merge($main, $test, $sort, $rank, $take)), $fail, $this->values)) {
          switch ($this->engine) {
            case self::POSTGRES;
              $rows = @pg_num_rows($this->result);
              break;
            case self::SQLITE:
              $rows = @sqlite_num_rows($this->result);
              break;
            case self::MYSQL:
              $rows = @mysqli_num_rows($this->result);
              break;
          }

          if (empty($keep)) {
            $this->from = [];

            $this->test = [];
          }

          $this->join = [];

          $this->rank = [];

          $this->sort = [];

          $this->load = [];

          $this->last = [];

          $this->skip = 0;

          $this->take = 0;

          return true;
        }
      }

      return false;
    }

    /** component functions */

    public function rows ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'COUNT', 'type' => self::INTEGER];
          }
        }
      }

      return $this;
    }

    public function sum ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'SUM', 'type' => self::INTEGER];
          }
        }
      }

      return $this;
    }

    public function avg ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'AVG', 'type' => self::INTEGER];
          }
        }
      }

      return $this;
    }

    public function min ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'MIN', 'type' => null];
          }
        }
      }

      return $this;
    }

    public function max ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'MAX', 'type' => null];
          }
        }
      }

      return $this;
    }

    public function most ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'MAX', 'type' => self::INTEGER];
          }
        }
      }

      return $this;
    }

    public function mean ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'AVG', 'type' => self::INTEGER];
          }
        }
      }

      return $this;
    }

    public function total ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->load[isset($this->last['from']) ? $this->last['from'] : $this->last['join']]
                       [($this->last['load'] = $name)] = ['nick' => $nick, 'name' => 'SUM', 'type' => self::DECIMAL];
          }
        }
      }

      return $this;
    }

    public function order ($name, $down = false) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if (isset($this->last['from'])) {
          $this->rank[$this->last['from']][] = compact('name', 'down');
        }
      } else {
        if (preg_match('/^(?P<from>\w+(_\w+)*)\.(?P<item>\w+(_\w+)*)$/i', $name, $name)) {
          $this->rank[$name['from']][] = ['down' => $down, 'name' => $name['item']];
        }
      }

      return $this;
    }

    public function group ($name) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((($main = isset($this->last['from'])) || isset($this->last['join']))) {
          $this->sort[$main ? $this->last['from'] : $this->last['join']][] = $name;
        }
      } else {
        if (preg_match('/^(?P<from>\w+(_\w+)*)\.(?P<item>\w+(_\w+)*)$/i', $name, $hash)) {
          $this->sort[$hash['from']][] = $hash['item'];
        } else {
            if (preg_match('/^(?P<name>\w+)\((?P<list>.*)\)$/i', $name, $hash)) {
                if ((($main = isset($this->last['from'])) || isset($this->last['join']))) {
                    $this->sort[$main ? $this->last['from'] : $this->last['join']][] = ['name' => $hash['name'], 'list' => $hash['list']];
                }
            }
        }
      }

      return $this;
    }

    public function from ($name, $nick = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          $this->last['from'] = ($nick ? $nick : $name);

          $this->last['join'] = null;

          if (empty(isset($this->from[$this->last['from']]))) {
            $this->from[$this->last['from']] = compact('name', 'nick');
          }
        }
      }

      return $this;
    }

    public function join ($name, $nick = null, $kind = 0x01) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if ((empty($nick) || preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $nick))) {
          $this->last['join'] = strtolower($nick ? $nick : $name);

          if (empty(isset($this->join[$this->last['join']]))) {
            $this->join[$this->last['join']] = compact('name', 'nick', 'kind');
          }
          
          unset($this->last['from']);
        }
      }

      return $this;
    }

    public function load ($data, $nick = null, $type = null) {
      if (preg_match('/^(\w+(_\w+)*)$/i', $data = is_array($data) ? @preg_replace_callback('/\{(?<item>[\d]+)(%(?<type>[b|d|f|s|x]))?\}/i', function ($hash) use ($data) {
        if (isset($data[$hash['item']])) {
          switch ((isset($hash['type']) ? strtolower($hash['type']) : 's')) {
            case 'b':
              return ($data[$hash['item']] ? 'true' : 'false');
            case 'd':
              return intval($data[$hash['item']]);
            case 'f':
              return floatval($data[$hash['item']]);
            case 's':
              return strval($data[$hash['item']]);
            case 'x':
              return dechex(intval($data[$hash['item']]));
          }
        }

        return $hash[0];
      }, strval($data[0])) : $data)) {
        if ((empty($nick) || preg_match('/^(\w+)$/i', $nick))) {
          if ((($main = isset($this->last['from'])) || isset($this->last['join']))) {
            $this->load[($main ? $this->last['from'] : $this->last['join'])][($this->last['load'] = strtolower($data))] = ['type' => $type,
                                                                                                                           'nick' => $nick,
                                                                                                                           'main' => $main ? $main : empty($this->join[$this->last['join']]['nick'])];
          }
        }  
      } else {
        if (preg_match('/^(?P<name>\w+)\((?P<list>.*)\)$/i', $data, $data)) {
          if (((($main = isset($this->last['from'])) || isset($this->last['join'])))) {
            $this->load[($main ? $this->last['from'] : $this->last['join'])][($this->last['load'] = strtolower($nick ? $nick : $data['name']))] = ['type' => $type,
                                                                                                                                                   'nick' => $nick,
                                                                                                                                                   'name' => $data['name'],
                                                                                                                                                   'list' => $data['list'],
                                                                                                                                                   'main' => $main ? $main : empty($this->join[$this->last['join']]['nick'])];
          }
        }
      }

      unset($this->last['fill']);

      return $this;
    }

    public function fill ($name, $data, $type = null) {
      if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
        if (isset($this->last['from'])) {
          $this->fill[$this->last['from']][($this->last['fill'] = strtolower($name))] = compact('name', 'data', 'type');
        }
      }

      $this->last['load'] = null;

      return $this;
    }

    public function type ($name) {
      if (isset($this->last['load'])) {
        if (isset($this->last['from'])) {
          $this->load[$this->last['from']][$this->last['load']]['type'] = $name;
        } else {
          if (isset($this->last['join'])) {
            $this->load[$this->last['join']][$this->last['load']]['type'] = $name;
          }
        }
      }

      return $this;
    }

    public function take ($value) {
      $this->take = max(intval($value), 0);

      return $this;
    }

    public function skip ($value) {
      $this->skip = max(intval($value), 0);

      return $this;
    }

    public function lock ($flag) {
      $this->lock = $flag;

      return $this;
    }

    /** clause functions */

    public function where ($name, $data, $sign = null, $flip = null, $join = true, $nest = null, $raw = false) {
      if (empty($raw)) {
        if (preg_match('/^([a-z]([a-z0-9])*(_([a-z0-9])+)*)$/i', $name)) {
          if ((isset($this->last['from']) || isset($this->last['join']))) {
            $this->test[isset($this->last['from']) ? $this->last['from'] : $this->last['join']][] = compact('name',
                                                                                                            'data',
                                                                                                            'sign',
                                                                                                            'flip',
                                                                                                            'join',
                                                                                                            'nest',
                                                                                                            'raw');
          }
        } else {
          if (preg_match('/^(?P<from>\w+(_\w+)*)\.(?P<item>\w+(_\w+)*)$/i', $name, $hash)) {
            $this->test[$hash['from']][] = ['name' => $hash['item'],
                                            'data' => $data,
                                            'sign' => $sign,
                                            'flip' => $flip,
                                            'join' => $join,
                                            'nest' => $nest,
                                            'raw' => $raw];
          } else {
            if (preg_match('/^(?P<name>\w+(_\w+)*)\((?P<list>.*)\)$/i', $name, $hash)) {
              if ((isset($this->last['from']) || isset($this->last['join']))) {
                $this->test[(isset($this->last['from']) ? $this->last['from'] : $this->last['join'])][] = ['name' => $hash['name'],
                                                                                                           'list' => $hash['list'],
                                                                                                           'data' => $data,
                                                                                                           'sign' => $sign,
                                                                                                           'flip' => $flip,
                                                                                                           'join' => $join,
                                                                                                           'nest' => $nest];
              }
            }
          }
        }
      } else {
        $this->test[isset($this->last['from']) ? $this->last['from'] : $this->last['join']][] = compact('name',
                                                                                                        'data',
                                                                                                        'sign',
                                                                                                        'flip',
                                                                                                        'join',
                                                                                                        'nest',
                                                                                                        'raw');
      }

      return $this;
      
    }

    public function like ($value) {
      return $this;

    }

    public function equal ($value) {
      return $this;
    }

    /** schema methods */

    public function schema ($table) {

    }

    public function boolean ($field) {

    }

    public function string ($field) {

    }

    public function float ($field) {

    }

    public function short ($field) {

    }

    public function long ($field) {

    }

    public function date ($field) {

    }

    public function time ($field) {

    }

    public function null ($flag) {

    }

    public function length ($value) {

    }

    public function defaults ($value) {

    }

    /** match functions */

    public function concatenate ($values, $glue = null) {
      if (isset($glue)) {

      } else {

      }
    }

    public function minimum ($field) {
      return sprintf('MIN(%s)', $this->cast(strval($field), true));
    }

    public function maximum ($field) {
      return sprintf('MAX(%s)', $this->cast(strval($field), true));
    }

    public function average ($field) {
      return sprintf('AVG(%s)', $this->cast(strval($field), true));
    }

    public function convert ($field, $type) {
      return sprintf('CONVERT(%s, %s)', $this->cast(strval($field), true), strval($type));
    }

    public function upper ($field) {
      return sprintf('UPPER(%s)', $this->cast(strval($field), true));
    }

    public function lower ($field) {
      return sprintf('LOWER(%s)', $this->cast(strval($field), true));
    }

    public function second ($date) {
      switch ($this->engine) {
        case self::POSTGRES:
          return sprintf("EXTRACT(SECOND FROM TIMESTAMP '%s')", $this->cast(strval($date), true));
        case self::SQLITE:
          break;
        case self::MYSQL:
          return sprintf('SECOND(%s)', $this->cast(strval($date), true));
      }

      return 'NULL';
    }

    public function minute ($date) {
      switch ($this->engine) {
        case self::POSTGRES:
          return sprintf("EXTRACT(MINUTE FROM TIMESTAMP '%s')", $this->cast(strval($date), true));
        case self::SQLITE:
          break;
        case self::MYSQL:
          return sprintf('MINUTE(%s)', $this->cast(strval($date), true));
      }

      return 'NULL';
    }

    public function hour ($date) {
      switch ($this->engine) {
        case self::POSTGRES:
          return sprintf("EXTRACT(HOUR FROM TIMESTAMP '%s')", $this->cast(strval($date), true));
        case self::SQLITE:
          break;
        case self::MYSQL:
          return sprintf('HOUR(%s)', $this->cast(strval($date), true));
      }

      return 'NULL';
    }

    public function month ($date) {
      switch ($this->engine) {
        case self::POSTGRES:
          return sprintf("EXTRACT(MONTH FROM TIMESTAMP '%s')", $this->cast(strval($date), true));
        case self::SQLITE:
          break;
        case self::MYSQL:
          return sprintf('MONTH(%s)', $this->cast(strval($date), true));
      }

      return 'NULL';
    }

    public function week ($date) {
      switch ($this->engine) {
        case self::POSTGRES:
          return sprintf("EXTRACT(WEEK FROM TIMESTAMP '%s')", $this->cast(strval($date), true));
        case self::SQLITE:
          break;
        case self::MYSQL:
          return sprintf('WEEK(%s)', $this->cast(strval($date), true));
      }

      return 'NULL';
    }

    public function year ($date) {
      switch ($this->engine) {
        case self::POSTGRES:
          return sprintf("EXTRACT(YEAR FROM TIMESTAMP '%s')", $this->cast(strval($date), true));
        case self::SQLITE:
          break;
        case self::MYSQL:
          return sprintf('YEAR(%s)', $this->cast(strval($date), true));
      }

      return 'NULL';
    }

    public function day ($date) {
      switch ($this->engine) {
        case self::POSTGRES:
          return sprintf("EXTRACT(DAY FROM TIMESTAMP '%s')", $this->cast(strval($date), true));
        case self::SQLITE:
          break;
        case self::MYSQL:
          return sprintf('DAY(%s)', $this->cast(strval($date), true));
      }

      return 'NULL';
    }

    /** result functions */

    public function fetch ($all, $name = null, $cache = null) {
      $result = $this->result;

      $rows = [];

      $row = [];

      if (isset($this->results[$cache])) {
        $result = $this->results[$cache];
      }

      switch ($this->engine) {
        case self::POSTGRES:
          if (isset($result)) {
            if ($every) {
              while (($row = ($map ? @pg_fetch_assoc($result) : @pg_fetch_row(($result))))) {
                if (isset($row[$field])) {
                  $row = $row[$field];
                }

                if (isset($row[$map])) {
                  $rows[$map] = $row;
                } else {
                  $rows[] = $row;
                }
              }
            } else {
              if ($map) {
                $row = @pg_fetch_assoc($result);
              } else {
                $row = @pg_fetch_row($result);
              }
            }
          }
          break;
        case self::SQLITE:
          if (isset($result)) {
            if ($every) {
              while (($row = $map ? @sqlite_fetch_array($result, SQLITE_ASSOC) : @sqlite_fetch_array($result, SQLITE_NUM))) {
                if (isset($raw[$field])) {
                  $row = $row[$field];
                }

                if (isset($row[$map])) {
                  $rows[$map] = $row;
                } else {
                  $rows[] = $row;
                }
              }
            } else {
              if ($map) {
                $row = @sqlite_fetch_array($result, SQLITE_ASSOC);
              } else {
                $row = @sqlite_fetch_array($result, SQLITE_NUM);
              }
            }
          }
          break;
        case self::MYSQL:
          if (isset($result)) {
            if ($all) {
              while (($row = $this->dumb($this->meta, @mysqli_fetch_row($result)))) {
                if (isset($row[$name])) {
                  $rows[] = $row[$name];
                } else {
                  $rows[] = $row;
                }
              }
            } else {
              $row = $this->dumb($this->meta, @mysqli_fetch_row($result));
            }
          }
          break;
      }

      return ($all ? $rows : (isset($row[$name]) ? $row[$name] : $row));
    }
  }
?>
