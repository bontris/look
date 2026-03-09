import React, {useMemo, useState, useEffect} from "react";

import moment from "moment";

import {
    useParams,
    useNavigate
} from "react-router-dom";

import {
    Box,
    Card,
    Grid,
    Menu,
    alpha,
    Stack,
    Paper,
    Alert,
    AppBar,
    Dialog,
    Drawer,
    Avatar,
    Button,
    Slider,
    Divider,
    Toolbar,
    SvgIcon,
    Backdrop,
    Collapse,
    Snackbar,
    MenuItem,
    ListItem,
    InputBase,
    TextField,
    CardHeader,
    Typography,
    IconButton,
    DialogTitle,
    FormControl,
    ListItemIcon,
    ListItemText,
    DialogContent,
    DialogActions,
    ListSubheader,
    ListItemAvatar,
    ListItemButton,
    InputAdornment,
    LinearProgress,
    CircularProgress
} from "@mui/material";

import {useApplication} from "../hooks/Application";

import {useSession} from "../hooks/Session";

import {BACK} from "./../environment";

import type {Null} from "../types/Null";

import type {Hash} from "../types/Hash";

import {Data} from "./../components/Data";

import {Form} from "./../components/Form";

import Firms from "./../services/Firms";

import Store from "./../services/Tests";

export namespace Tests {
    const list = [
        {item: 1, sort: 0, rate: 0, text: 'Asuntos corporativos'},
        {item: 1, sort: 1, rate: 10, text: 'Tienes los estatutos de tu empresa compilados y actualizados (incluyendo cualquier modificación efectuada al documento de constitución).'},
        {item: 1, sort: 2, rate: 10, text: 'Tienes en tu poder el Libro de Registro de Accionistas registrado ante la Cámara de Comercio y de los títulos de acciones existentes.'},
        {item: 1, sort: 3, rate: 10, text: 'Tienes en tu poder el Libro de Actas de Asamblea registrados ante la Cámara de Comercio.'},
        {item: 1, sort: 4, rate: 10, text: 'Existe en la empresa algun derecho, garantía u opción de compra u otros derechos para adquirir acciones o no existen ninguno de los anteriores'},
        {item: 1, sort: 5, rate: 10, text: 'Existen acuerdos de compra de acciones, o no existen ninguno de los anteriores.'},
        {item: 1, sort: 6, rate: 10, text: 'La empresa a otorgado poderes a terceros o a sus empleados para representarla ante alguna entidad o proceso'},
        {item: 1, sort: 7, rate: 10, text: 'La empresa ha realizado préstamos a los accionistas, o los accionistas han realizados préstamos a la empresa, están documentos y tienes copia de esta documentación '},
        {item: 1, sort: 8, rate: 10, text: 'Cuentas con los Estados financieros de la Compañía de los últimos cinco (5) años y copia de cualquier dictamen del revisor fiscal o contador público.'},
        {item: 1, sort: 9, rate: 10, text: 'Han realizado comunicaciones con la Superintendencia de Sociedades y tienes copia de estos procesos'},
        {item: 1, sort: 10, rate: 10, text: 'Cuentas con los recibos/pruebas de depósito de los estados financieros en la Cámara de Comercio de los últimos cinco (5) años (Artículo 41 de la Ley 222 de 1995).'},
        {item: 2, sort: 0, rate: 0, text: 'Asuntos cambiarios'},
        {item: 2, sort: 1, rate: 30, text: 'Realizas operaciones de cambio de moneda, cuentas con las copias de todas las declaraciones de cambio por endeudamiento, importación, exportación, adquisición, venta, u otros conceptos presentados ante el Banco de la República de Colombia.'},
        {item: 2, sort: 2, rate: 30, text: 'Cuentas con las copia de todas las declaraciones de divisas por inversión extranjera directa, endeudamiento u otros conceptos presentados ante el Banco de la República y la información de las cuentas de compensación. Confirmación en caso de no existir endeudamiento externo o inversión extranjera directa o cuentas de compensación.'},
        {item: 2, sort: 3, rate: 40, text: 'Cuentas con las copia de cualquier comunicación recibida del Banco de la República de Colombia y las respuestas enviadas por la Compañía al Banco de la República de Colombia.'},
        {item: 3, sort: 0, rate: 0, text: 'Asuntos laborales'},
        {item: 3, sort: 1, rate: 8, text: 'La empresa tiene empleados, Cuentas con una lista de todos los empleados (nacionales o extranjeros), incluyendo la fecha del contrato, tipo de contrato, duración (término fijo o indefinido), monto del salario y tipo de salario, régimen de cesantías si aplica, vacaciones pendientes, licencias (ejemplo, permiso de maternidad, incapacidad, permiso sindical etc.) de la Compañía en los tres (3) últimos años.'},
        {item: 3, sort: 2, rate: 8, text: 'La empresa tiene aprendices del SENA, cuentas con la lista de personas contratadas mediante contrato de aprendizaje, incluyendo la fecha del contrato y la Resolución del SENA en los últimos tres (3) años.'},
        {item: 3, sort: 3, rate: 8, text: 'Tienes los contratos de trabajo documentados, cuentas con la copia de los contratos de trabajo de los empleados de la Compañía, incluyendo modificaciones y anexos.'},
        {item: 3, sort: 4, rate: 8, text: 'La empresa tienen personal contrato con empresas de trabajo temporales, cuentas con el listado del personal contratado a través de outsourcing, servicios temporales, cooperativas de trabajo asociado, etc., señalando antigüedad (incluyendo contrataciones previas si no ha habido interrupción) y copia de los Contratos tanto de la Compañía con la empresa outsourcing o temporal, así como los formatos de los contratos usados por ésta con los trabajadores en los tres (3) últimos años.'},
        {item: 3, sort: 5, rate: 8, text: 'La empresa tiene contratistas personas naturales, cuentas con el listado de contratistas de la Compañía en los tres (3) últimos años y los servicios que prestan, y confirmación de si dichos contratistas fueron afiliados al sistema de seguridad social o al sistema de riesgos laborales.'},
        {item: 3, sort: 6, rate: 8, text: 'La empresa ha realizado préstamos a los empleados, cuentas con la lista de préstamos a los empleados de la Compañía y comprobantes de las garantías y autorizaciones de descuento de los salarios y beneficios sociales de los empleados en los tres (3) últimos años. Copia de las autorizaciones de descuento en los casos en los que la Compañía descuenta los salarios de los empleados (por ejemplo, nóminas).'},
        {item: 3, sort: 7, rate: 8, text: 'La compañía ha sido demandada por asuntos laborales, de ser aplicable, tienes la lista de las demandas laborales que la Compañía tiene en su contra. Acceso a los expedientes pertinentes (incluyendo cualquier solicitud o proceso iniciado por la Unidad de Gestión Pensional y Parafiscal - UGPP y el Ministerio de Trabajo).'},
        {item: 3, sort: 8, rate: 8, text: 'Cuentas con la copia de la última nómina pagada por la Compañía. Información relativa a la remuneración variable (comisiones, dietas, viáticos, bonos, primas, asignaciones etc.) y si dichos pagos se consideran parte constitutiva del salario del empleado. Descripción de cualquier plan de acciones u opciones sobre acciones aplicable a los empleados.'},
        {item: 3, sort: 9, rate: 8, text: 'Cuentas con la evidencia de pago de beneficios sociales obligatorios (prestaciones sociales cesantía, interés y prima de servicios), subsidio de transporte, etc. Información relacionada con el trabajo suplementario u horas extras e indicación sobre la forma en que la Compañía compensa por ese trabajo del total de número de trabajadores en los tres (3) últimos años.'},
        {item: 3, sort: 10, rate: 8, text: 'Cuentas con la copia del PILA (planillas de liquidación de aportes) del total de número de trabajadores en los tres (3) últimos años.'},
        {item: 3, sort: 11, rate: 8, text: 'Confirmar si la Compañía tiene vigente el Programa de Salud Ocupacional (ahora llamado el Sistema de Gestión de Seguridad y Salud en el Trabajo) y el Panorama de Factores de Riesgo. Confirmar si están firmados por el Representante Legal, el responsable de seguridad y salud en el trabajo y el asesor de la ARL. Proporcionar el estado actual del proceso.'},
        {item: 3, sort: 12, rate: 12, text: 'En la compañía se han presentado accidentes de trabajo, cuentas con los registros de accidentes de trabajo y enfermedades profesionales de trabajadores y contratistas de la Compañía en los últimos tres (3) años.'},
        {item: 4, sort: 0, rate: 0, text: 'Asuntos de propiedad intelectual'},
        {item: 4, sort: 1, rate: 9, text: 'Cuentas con la Información sobre todos los derechos de propiedad intelectual utilizados y/o registrados por la Compañía en Colombia o en el exterior (derechos de propiedad intelectual incluyen patentes, modelos de utilidad, marcas comerciales, nombres comerciales y comerciales, nombres de dominio y URL, diseños, derechos de autor y derechos relacionados, software) y confirme si las marcas registradas se están utilizando y si han sido autorizados a terceros.'},
        {item: 4, sort: 2, rate: 9, text: 'La compañía ha tenido disputas o conflictos relaciones con Información relacionada con disputas, quejas u objeciones relacionadas con los derechos de propiedad intelectual.'},
        {item: 4, sort: 3, rate: 9, text: 'La compañía ha otorgado o le han otorgado acuerdos, licencias y sublicencias de derechos de propiedad intelectual, tienes copia de estos acuerdos.'},
        {item: 4, sort: 4, rate: 9, text: 'La empresa ha firmado formatos con los empleaos, cuentas con las copias de los formatos a ser suscritos por empleados, consultores o terceros, cuando se contrata trabajo por encargo, contratos laborales, cláusulas de no competencia o confidencialidad utilizados por la Compañía.'},
        {item: 4, sort: 5, rate: 9, text: 'La empresa tiene activos de propiedad intelectual, cuentas con el listado de cualquier otra Propiedad Intelectual que sea relevante para la Compañía. (Patentes, modelos de utilidad, diseños industriales, marcas, logos o diseños que la Compañía utilice así no estén registrados ante la Autoridad.'},
        {item: 4, sort: 6, rate: 9, text: 'La empresa tiene cualesquiera contratos o acuerdos con terceros que regule o restrinja el uso de cualquier propiedad intelectual que pertenezca a terceros y que sea utilizada por la Compañía. Cuentas con copia de estos acuerdos.'},    
        {item: 4, sort: 7, rate: 9, text: 'La empresa cuenta con Políticas internas en relación con información confidencial, secretos industriales, know-how y otra información propietaria o de acceso de la Compañía.'},
        {item: 4, sort: 8, rate: 9, text: 'La empresa tiene desarrollos tecnológicos, nos podrías dar una descripción de la(s) plataforma(s) y software(s) que estén siendo utilizadas por la Compañía, así como una explicación respecto de si las mismas han sido desarrolladas internamente por la Compañía y/o si son contratadas de terceras personas, adjuntando copia de los mencionados contratos de licencia.'},
        {item: 4, sort: 9, rate: 9, text: 'La empresa contrata servicios en la nube, cuentas con el listado de los sistemas contratados que involucren servicios alojados en la nube, copia de los mencionados contratos.'},
        {item: 5, sort: 0, rate: 0, text: 'Asuntos contractuales'},
        {item: 5, sort: 1, rate: 0, text: 'Cuentas con la lista de los principales acuerdos vigentes celebrados por la Compañía, incluidos los acuerdos verbales, que indiquen su propósito, alcance, duración y principales obligaciones. Específicamente, aquellos ejecutados fuera del curso ordinario de los negocios de la Compañía, indicando el monto (si corresponde) e incluyendo una breve descripción.'},
        {item: 5, sort: 2, rate: 0, text: 'Cuentas con la copia de los acuerdos mencionados anteriormente, incluidos los acuerdos celebrados con proveedores, clientes, cualquier entidad gubernamental o gubernamental, acuerdos de colaboración o joint venture, acuerdos de fideicomiso, acuerdos o acuerdos de confidencialidad y de no competencia que limiten las actividades en un mercado o área geográfica específica.'},
        {item: 5, sort: 3, rate: 0, text: 'Realizas operaciones con partes vinculadas (socios, filiales, matrices, subsidiarias. En caso de aplicar cuentas con la lista y copia de los contratos celebrados con partes vinculadas.'},
        {item: 5, sort: 4, rate: 0, text: 'Favor confirmar si existen reclamaciones contractuales por parte de clientes (especialmente distribuidores) y, de ser así, proporcionar la información y documentación pertinente.'},
        {item: 6, sort: 0, rate: 0, text: 'Asuntos de litigios'},
        {item: 6, sort: 1, rate: 0, text: 'La empresa tiene en proceso litigios, arbitrajes, reclamos, demandas, investigaciones o procesos administrativos existentes, pendientes de los cuales la Compañía o cualquiera de sus empleados es parte debido a hechos relacionados con el desempeño de sus funciones, junto con una indicación de la cantidad y probabilidad de éxito para el demandante.'},
        {item: 7, sort: 0, rate: 0, text: 'Asuntos de seguridad de la información y tratamiento de datos personales'},
        {item: 7, sort: 1, rate: 0, text: 'Describe la estructura de manejo de la privacidad y datos de la Compañía, y suministro de los documentos correspondientes.'},
        {item: 7, sort: 2, rate: 0, text: 'Cuentas con las copia de todas las autorizaciones, avisos y documentos relativos a uso y manejo de datos personales en la Compañía.'},
        {item: 7, sort: 3, rate: 0, text: 'Realizas transferencia y/o transmisión de datos personales, De ser aplicable cuentas con la copia de todos los contratos de transferencia o transmisión de datos personales celebrados con terceros.'},
        {item: 7, sort: 4, rate: 0, text: 'La empresa tiene  Política de Privacidad adoptada por la Compañía y los Manuales Internos relacionados con el manejo de datos personales.'},
        {item: 7, sort: 5, rate: 0, text: 'Confírmanos si  la Compañía implementa mecanismos de videovigilancia en sus instalaciones. En caso afirmativo, favor compartir una copia de los avisos de videovigilancia.'},
        {item: 7, sort: 6, rate: 0, text: 'Confirmación de si la Compañía ha designado un oficial de protección de datos (DPO) que atienda quejas y/o reclamos de la operación en Colombia y copia del documento de nombramiento.'},
        {item: 7, sort: 7, rate: 0, text: 'Cuentas con manuales y políticas internas en materia de seguridad de la información donde se establezcan las medidas técnicas, administrativas y humanas que han sido implementados por la compañía para salvaguardar la información, o copia de certificaciones de estándares internacionales de seguridad de la información (i.e. ISO 270001).'},
        {item: 7, sort: 8, rate: 0, text: 'Confirmación que la Compañía no cuente con ningún proceso ante la delegatura de protección de datos personales de la Superintendencia de Industria y Comercio, y que no han sufrido incidentes de seguridad donde la disponibilidad, integridad y/o confidencialidad de la información personal se haya visto afectada en los últimos cinco (5) años.'},
        {item: 7, sort: 9, rate: 0, text: 'Confirmación que la compañía tiene activos totales superiores a 100.000 UVT y, en caso afirmativo, que ya ha realizado el registro de la base de datos ante el Registro Nacional de Bases de Datos (RNBD) de la SIC.'},
        {item: 8, sort: 0, rate: 0, text: 'Asuntos de seguros'},
        {item: 8, sort: 1, rate: 0, text: 'La empresa cuenta con polizas de seguros. Lista de todas las pólizas de seguro vigentes que tiene la Compañía a la fecha, incluyendo condiciones generales y específicas, recibo del pago de las primas y certificados de seguro, identificando a la compañía aseguradora y el tipo de póliza, y proporcionar la documentación correspondiente. Indicar si han ocurrido siniestros bajo el amparo de las pólizas.'},
        {item: 9, sort: 0, rate: 0, text: 'Asuntos inmobiliarios'},
        {item: 9, sort: 1, rate: 0, text: 'La empresa tiene bienes inmuebles. Lista de todos los bienes inmuebles sobre los que tenga interés la Compañía, con indicación del propietario, ubicación, extensión y uso de los inmuebles. Esto incluye inmuebles de propiedad de la Compañía, arrendados o sobre los que tengan, entre otros, derecho de uso o habitación, comodato, usufructo, nuda propiedad, promesa de compraventa, leasing.'},
        {item: 9, sort: 2, rate: 0, text: 'Cuentas con la copia de los contratos, resoluciones, sentencias, oficios y/o escrituras públicas, entre otros, relacionados con los inmuebles de la lista de la sección 9.1 anterior. Basta copia simple con todos los anexos.'},
        {item: 9, sort: 3, rate: 0, text: 'Cuenta con los folios de matrícula inmobiliaria de todos los inmuebles de propiedad de la Compañía, expedidos con no más de siete (7) días de antelación.'},
        {item: 9, sort: 4, rate: 0, text: 'Tienes las escrituras públicas, sentencias, oficios y/o resoluciones registrados en los folios de matrícula inmobiliaria de los inmuebles enlistados en la tabla referenciada en la sección 9.1 anterior, donde consten transferencias del derecho de dominio a cualquier título de los últimos 20 años (por ejemplo, compraventa, aporte a sociedad, restitución de fiducia mercantil, herencia, entre otros), gravámenes, limitaciones al dominio, o en general de cualquier tema relevante como englobes, desenglobes, reconocimientos de construcción, entre otros. Basta copia simple con todos los anexos.'},
        {item: 9, sort: 5, rate: 0, text: 'La empresa tiene en desarrollo litigios, arbitramentos, reclamaciones, requerimientos, investigaciones o procesos administrativos existentes o que la Compañía prevea que pueden existir en el futuro, relacionados con los inmuebles enlistados en la tabla solicitada en la sección 9.1 anterior. En caso de no aplicar, por favor adjuntar una declaración suscrita por el representante legal de la Compañía.'},
        {item: 9, sort: 6, rate: 0, text: 'Se ha presentado algún incumplimiento por parte de la empresa o de terceros relacionados con los inmuebles. Cuentas con la documentación referente al incumplimiento (real o presunto) de cualquiera de los contratos referidos anteriormente.'},
        {item: 9, sort: 7, rate: 0, text: 'Estas al día con los impuestos relacionados con los inmuebles. Cuentas con los  Paz y Salvos de Impuesto Predial y Valorización de los inmuebles enlistados en la tabla solicitada en la sección 9.1 anterior.'},
        {item: 9, sort: 8, rate: 0, text: 'Estas al día con la administración de los inmuebles, cuentas con los Paz y Salvos de administración de los inmuebles enlistados en la tabla solicitada en la sección 9.1 anterior, en caso de aplicar.'},
        {item: 9, sort: 9, rate: 0, text: 'Cuentas con el certificado expedido por la URT, donde conste que las propiedades de la Compañía (propias o que utiliza a cualquier título) no se encuentran dentro de zonas macro y micro focalizadas de restitución de tierras y en el cual conste que los inmuebles no tienen procesos jurídicos activos o pendientes por restitución de tierras. Este certificado no debe tener una fecha de expedición superior a un mes.'},
        {item: 9, sort: 10, rate: 0, text: 'Cuentas con los estudios de títulos sobre los inmuebles enlistados en la tabla requerida en la sección 9.1 anterior (en caso de existir).'},
        {item: 9, sort: 11, rate: 0, text: 'Cuentas con los comprobantes de pago de los montos debidos bajo los títulos de transferencia de propiedad o promesas.'},
        {item: 10, sort: 0, rate: 0, text: 'Asuntos ambientales'},
        {item: 10, sort: 1, rate: 0, text: 'Tu empresa realiza actividades que su actividad tenga que considerar asuntos de normas ambientales. Si aplica, copia de todas las licencias, permisos, autorizaciones, registros ambientales expedidos por las autoridades ambientales competentes para cada una de las sedes de la Compañía, incluyendo, pero sin limitarse a: permiso de vertimientos, permiso de emisiones atmosféricas, registros de publicidad exterior visual para los avisos de cada una de las sedes y/o para cada uno de los vehículos publicitarios de la Compañía, etc.'},
        {item: 10, sort: 2, rate: 0, text: 'Cuenta con la copia de los planes de manejo ambiental, estudios de impacto ambiental, diagnóstico ambiental de alternativas y planes de contingencia de la Compañía, si resulta aplicable.'},
        {item: 10, sort: 3, rate: 0, text: 'Cuentas con la copia de todos los informes internos y externos u otros documentos que describan la situación ambiental de la Compañía, si resulta aplicable.'},
        {item: 10, sort: 4, rate: 0, text: 'Cuentas con el listado, descripción y estado actual de los requerimientos, procesos en curso (administrativos y/o judiciales), medidas preventivas o temporales vigentes, (v.g. tutelas, acciones de grupo y populares) relacionadas con incumplimientos de carácter ambiental, así como toda correspondencia u otras comunicaciones entre la Compañía o sus subsidiarias y cualquier autoridad ambiental en relación con investigaciones o procesos judiciales o administrativos. '},
        {item: 10, sort: 5, rate: 0, text: 'Cuentas con copia de la correspondencia cruzada con las autoridades ambientales competentes durante los últimos cinco (5) años, incluyendo, pero sin limitarse a: requerimientos, autos de seguimiento, actas de visitas técnicas y/o conceptos técnicos emitidos por dichas autoridades para cada una de las sedes de la Compañía y las respuestas dadas a requerimientos.'},
        {item: 10, sort: 6, rate: 0, text: 'Cuentas con la copia de los tres (3) últimos Informes de Cumplimiento Ambiental (ICA) presentados ante la autoridad ambiental competente, si aplica.'},
        {item: 10, sort: 7, rate: 0, text: 'Eres acoplador de aceite usado. Cuentas con las opias de los registros de la Compañía como acopiador de aceite usado ante las autoridades ambientales competentes, en el evento en el que este requisito aplique.'},
        {item: 10, sort: 8, rate: 0, text: 'Cuentas con la copia del plan de contingencia presentado a la autoridad ambiental competente y de aquellos documentos que registren o den cuenta de asuntos ambientales relevantes (incidentes/emergencias), incluyendo reportes de monitoreo, accidentes o enfermedades ocupacionales, derrames, incendios y otras emergencias.'},
        {item: 10, sort: 9, rate: 0, text: 'Cuentas con la Constancia del cumplimiento del Decreto 1609 de 2002, para el transporte de mercancías y residuos peligrosos.'},
        {item: 10, sort: 10, rate: 0, text: 'En caso de que la Compañía sea generadora de RESPEL, indicar si cada una de las sedes de la Compañía cuenta con el Registro de Generadores de Residuos Peligrosos (RESPEL) ante las respectivas autoridades ambientales. En caso afirmativo, remitir evidencia de la presentación de los tres (3) últimos reportes anuales de generación de RESPEL presentados a través de la plataforma del IDEAM, para cada una de las sedes de la Compañía. '},
        {item: 10, sort: 11, rate: 0, text: 'Suministrar copias de los certificados de disposición final de RESPEL emitidos en los últimos cinco (5) años por las empresas que realizan la disposición final de estos residuos en cada una de las sedes de la Compañía. '},
        {item: 10, sort: 12, rate: 0, text: 'Suministrar copia de las licencias y/o autorizaciones ambientales con las que cuentan las empresas gestoras contratadas por la Compañía para la recolección, transporte, tratamiento y disposición final del RESPEL generado por la Compañía.'},
        {item: 10, sort: 13, rate: 0, text: 'Indicar si la Compañía genera residuos de aparatos eléctricos y electrónicos (RAEES) en alguna(s) de sus sedes. En caso afirmativo, remitir copia de los certificados de recolección y/o de disposición final de este tipo de residuos, expedidos en los últimos tres (3) años por terceros autorizados mediante licencias y permisos ambientales vigentes.'},
        {item: 10, sort: 14, rate: 0, text: 'Indicar si la Compañía es propietaria de máquinas o equipos que contengan o estén contaminados con Bifelinos Policlorados (PCBs). En caso afirmativo, remitir una copia del registro del inventario de PCBs de que trata la Resolución 222 de 2011.'},
        {item: 10, sort: 15, rate: 0, text: 'Informe sobre la existencia de algún pasivo ambiental, tal como suelos y/o aguas subterráneas contaminadas o enterramiento de sustancias peligrosas dentro de las instalaciones o terrenos en los cuales opera la Compañía. Suministrar estudios de identificación y evidencia de remediación, en caso de aplicar.'},
        {item: 10, sort: 16, rate: 0, text: 'Copia del programa de ahorro y uso eficiente del agua y de la presentación del mismo ante la autoridad ambiental.'},
        {item: 10, sort: 17, rate: 0, text: 'Favor suministrar copia del Registro Único Ambiental.'},
        {item: 10, sort: 18, rate: 0, text: 'Informar si la Compañía maneja sustancias químicas controladas por la Subdirección de Control y Fiscalización de Sustancias Químicas y Estupefacientes (antes Dirección Nacional de Estupefacientes). Si su respuesta es afirmativa, favor suministrar copia del Certificado de Carencia de Informes por Tráfico de Estupefacientes CCITES.'},
        {item: 10, sort: 19, rate: 0, text: 'Informar si hay comunidades étnicas en el área de influencia directa de la operación de la Compañía. De ser afirmativo, informar si tales comunidades étnicas han hecho requerimientos de consulta previa.'},
        {item: 11, sort: 0, rate: 0, text: 'Asuntos regulatorios'},
        {item: 11, sort: 1, rate: 0, text: 'Tu empresa es regulada o supervisada por alguna entidad. Cuentas con la lista de las resoluciones, licencias, permisos y/o demás autorizaciones obtenidas por la Compañía ante autoridades competentes, para el debido desarrollo de su objeto social en el mercado.'},
        {item: 11, sort: 2, rate: 0, text: 'Copia de los documentos referidos en el numeral anterior.'}
    ];

    export const List = ({task}: {task?: string}) => {
        const {
            setAlert,
            setTitle,
            setDialog
        } = useApplication();

        const navigate = useNavigate();

        const {session} = useSession();

        const [take, setTake] = useState(16);

        const [page, setPage] = useState(1);

        const [data, setData] = useState({
            find: null as Null<string>,
            sort: {} as Hash<boolean>,
            play: false,
            wait: true,
            firm: null,
            dash: '',
            take: 16,
            page: 0,
            size: 0,
            done: 0,
            time: 0,
            item: 0,
            list: [],
            pipe: {
            } as Hash<{data: Null<any>, sign: '~' | '=' | '<' | '>' | '!'}>
        });

        useEffect(() => {
            Store.load(data.take, data.page, data.find, Object.keys(data.pipe).filter((name) => ((typeof data.pipe[name]['data'] == 'number') || Boolean(data.pipe[name]['data']))).map((name) => {
                return `${name}: ${(data.pipe[name]['sign'])} ${(data.pipe[name]['data'] instanceof Array ? data.pipe[name]['data'].join(' ') : data.pipe[name]['data'])}`
            }, []).join(', '), null, data.sort, (done, data) => {
                if (done) {
                    setData((last) => ({...last, wait: false, page: data.page, list: data.data, size: data.size}));
                } else {
                    //setNote({show: true, type: 'error', text: 'No se pudo cargar los registros.'});

                    setData((last) => ({...last, list: [], wait: false}));
                }
            });
        }, [data.page, data.take, data.sort, data.find, data.pipe]);
    
        return (
            <Grid
                direction="column"
                display="flex"
                spacing={2}
                container>
                <Grid item>
                    <Typography
                        color="text.primary"
                        variant="h4"
                        gutterBottom>
                        Listado de Diagnósticos
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de diagnósticos
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    <Data
                        seek={'Id'}
                        name=""
                        menu={[
                            {
                                type: 'push',
                                name: 'make',
                                show: ([1, 2, 3].includes(session.type)),
                                view: (lock: boolean) => (
                                    <Button
                                        sx={{padding: '2px 6px 2px 16px'}}
                                        color="primary"
                                        variant="contained"
                                        disabled={lock}
                                        startIcon={(
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none">
                                                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                                    <path d="M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v0a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2m0 12v-5m3 5v-1m3 1v-3" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        endIcon={(
                                            <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="none"
                                                    fill="currentColor">
                                                    <path d="M4.929 4.929A10 10 0 1 1 19.07 19.07A10 10 0 0 1 4.93 4.93zM13 9a1 1 0 1 0-2 0v2H9a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2z" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        onClick={(event) => {
                                            navigate(`/diagnosticos/make`);
                                        }}>
                                        Agregar
                                    </Button>
                                )
                            }
                        ]}
                        dash={[
                            {
                                icon: ['M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'],
                                hint: 'Descargar',
                                name: 'View',
                                lock: (item: any) => ((Boolean(item.file) == false)),
                                view: (item: any, lock: boolean) => (
                                    <Button
                                        sx={{padding: '2px 6px 2px 16px'}}
                                        color="primary"
                                        variant="contained"
                                        disabled={lock}
                                        startIcon={(
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none">
                                                    <path d="M6.657 18C4.085 18 2 15.993 2 13.517s2.085-4.482 4.657-4.482c.393-1.762 1.794-3.2 3.675-3.773c1.88-.572 3.956-.193 5.444 1c1.488 1.19 2.162 3.007 1.77 4.769h.99c1.913 0 3.464 1.56 3.464 3.486s-1.551 3.487-3.465 3.487H6.657" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        endIcon={(
                                            <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="none"
                                                    fill="currentColor">
                                                    <path d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12 7a1 1 0 0 0-1 1v5.585l-2.293-2.292l-.094-.083a1 1 0 0 0-1.32 1.497l4 4q.04.04.094.083l.092.064l.098.052l.081.034l.113.034l.112.02L12 17l.115-.007l.114-.02l.142-.044l.113-.054l.111-.071a1 1 0 0 0 .112-.097l4-4l.083-.094a1 1 0 0 0-1.497-1.32L13 13.584V8l-.007-.117A1 1 0 0 0 12 7" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        onClick={(event) => {
                                            window.open(`${BACK}/files/save/${item.file}`, '_blank');
                                        }}>
                                        Informe
                                    </Button>
                                )
                            },
                            {
                                icon: ['M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'],
                                hint: 'Detalles',
                                name: 'View',
                                view: (item: any, lock: boolean) => (
                                    <Button
                                        sx={{padding: '2px 6px 2px 16px'}}
                                        color="primary"
                                        variant="contained"
                                        disabled={lock}
                                        startIcon={(
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none">
                                                    <path d="M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        endIcon={(
                                            <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="none"
                                                    fill="currentColor">
                                                    <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4z" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        onClick={(event) => {
                                            navigate(`/diagnosticos/view/${item.hash}`);
                                        }}>
                                        Detalles
                                    </Button>
                                )
                            },
                            {
                                icon: ['M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'],
                                hint: 'Editar',
                                name: 'Edit',
                                show: ([1, 2, 3].includes(session.type)),
                                view: (item: any, lock: boolean) => (
                                    <Button
                                        sx={{padding: '2px 6px 2px 16px'}}
                                        color="primary"
                                        variant="contained"
                                        disabled={lock}
                                        startIcon={(
                                            <SvgIcon sx={{width: '24px', height: '24px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="currentColor"
                                                    fill="none">
                                                    <path d="M7 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-1" />,
                                                    <path d="M20.385 6.585a2.1 2.1 0 0 0-2.97-2.97L9 12v3h3zM16 5l3 3" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        endIcon={(
                                            <SvgIcon sx={{width: '38px', height: '38px'}}>
                                                <g
                                                    strokeLinejoin="round"
                                                    strokeLinecap="round"
                                                    strokeWidth="2"
                                                    stroke="none"
                                                    fill="currentColor">
                                                    <path d="m12 2l.324.005a10 10 0 1 1-.648 0zm.613 5.21a1 1 0 0 0-1.32 1.497L13.584 11H8l-.117.007A1 1 0 0 0 8 13h5.584l-2.291 2.293l-.083.094a1 1 0 0 0 1.497 1.32l4-4l.073-.082l.064-.089l.062-.113l.044-.11l.03-.112l.017-.126L17 12l-.007-.118l-.029-.148l-.035-.105l-.054-.113l-.071-.111a1 1 0 0 0-.097-.112l-4-4z" />
                                                </g>
                                            </SvgIcon>
                                        )}
                                        onClick={(event) => {
                                            navigate(`/diagnosticos/edit/${item.hash}`);
                                        }}>
                                        Editar
                                    </Button>
                                )
                            }
                        ]}
                        data={[
                            {
                                item: 'code',
                                name: 'Código',
                                size: 180,
                                show: true,
                                sort: true
                            },
                            {
                                item: 'firm',
                                name: 'Empresa',
                                show: true,
                                sort: true
                            },
                            {
                                item: 'made',
                                name: 'Creación',
                                edge: 'right',
                                show: true,
                                sort: true,
                                size: 180,
                                cast: (item: any) => (moment(item.made).format('DD/MM/YY LT'))
                            }
                        ]}
                        take={{
                            pick: data.take ?? 16,
                            list: [16,  32, 64]
                        }}
                        find={{
                            hint: 'Buscar',
                            text: data.find
                        }}
                        none={{
                            text: 'Sin diagnósticos',
                            note: 'No se encontraron diagnósticos disponibles.'
                        }}
                        wait={data.wait}
                        list={data.list}
                        size={data.size}
                        page={data.page}
                        load={(page, take, find, pipe, sort) => {
                            setData((last) => ({...last, page: page ?? 1, take: take ?? 16, sort: sort, find: find, pipe: pipe, wait: true}));
                        }} />
                </Grid>
            </Grid>
        );
    }

    export const Make = () => {
        const [data, setData] = useState<{wait: boolean, lock: boolean, fail: Hash<string>, heap: Hash<any>, form: Hash<any>}>({
            wait: false,
            lock: false,
            heap: {
                firm: {
                    wait: false,
                    fail: false,
                    take: 0,
                    size: 0,
                    list: []
                }
            },
            form: {
                bind: null,
                area: null,
                work: null,
                file: null,
                list: list.map((data, next) => (
                    {
                        item: next,
                        data: 0,
                        risk: 0,
                        note: '',
                        plan: '',
                        text: data.text,
                        type: data.item,
                        sort: data.sort,
                        rate: data.rate
                    }
                ))
            },
            fail: {}
        });

        const {session} = useSession();

        const rate = useMemo(() => (
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11].map((type) => {
                let bulk = data.form.list?.filter((next: any) => (((next.type == type) && ((next.data == 0) || (next.data == 1) || (next.data == 2))))) ?? [];

                return {type: type, name: list.find((item: any) => (item.item == type))?.text ?? null, rate: (bulk.length ? ((bulk.filter((item: any) => (item.data == 1)).length * 100) / bulk.length) : 0)};
            })
        ), [data.form.list]);

        const view:any = useMemo(() => (
            {
                data: [
                    {
                        step: 0,
                        line: true,
                        name: 'Main',
                        text: 'Básica',
                        hint: 'Información básica'
                    },
                    {
                        type: 'list',
                        size: 'full',
                        name: 'bind',
                        text: 'Compañia',
                        bind: 'La opción es requerida.',
                        list: session.heap?.firm?.map((next: any) => ({item: next.item, text: next.name, hint: next.card})) ?? []
                    },
                    {
                        type: 'text',
                        size: 'full',
                        name: 'area',
                        text: 'Sector'
                    },
                    {
                        type: 'area',
                        size: 'full',
                        name: 'work',
                        text: 'Actividades'
                    },
                    {
                        type: 'file',
                        size: 'full',
                        name: 'file',
                        text: 'Reporte'
                    },
                    {
                        line: true,
                        name: 'Test',
                        text: 'Preguntas',
                        hint: 'Cuestionario de diagnóstico'
                    },
                    {
                        tile: true,
                        grid: true,
                        type: 'text',
                        size: 'full',
                        name: 'list',
                        text: 'Add aditional phone',
                        foot: (item: any) => (item.sort ? item : (
                            <Stack>
                                <Typography
                                    sx={(theme) => ({color: theme.palette.primary.main})}
                                    variant="h6">
                                    {item.type}. {(item = rate.find((some: any) => (some.type == item.type)))?.name}
                                </Typography>
                                <Slider sx={(theme) => (
                                    {
                                        borderRadius: 8,
                                        borderColor: 'currentColor',
                                        '& .MuiSlider-thumb': {
                                            height: 32,
                                            width: 32,
                                            backgroundImage: 'url(/images/icons/mark.svg)',
                                            backgroundRepeat: 'no-repeat',
                                            backgroundSize: '28px 28px',
                                            backgroundColor: 'transparent',
                                            backgroundPosition: 'center',
                                            boxShadow: 'none'
                                        },
                                        '& .MuiSlider-thumb::before': {
                                            display: 'none'
                                        },
                                        '& .MuiSlider-thumb::after': {
                                            display: 'none',
                                        },
                                        '& .MuiSlider-root.Mui-disabled': {
                                            color: theme.palette.primary.main
                                        },
                                        '& .MuiSlider-track': {
                                            display: 'none'
                                        },
                                        '& .MuiSlider-rail': {
                                            background: 'linear-gradient(to right, red, orange, yellow, green)',
                                            opacity: 1,
                                            height: 6
                                        },
                                        '& .MuiSlider-valueLabel': {
                                            background: theme.palette.primary.main
                                        }
                                    }
                                )}
                                min={0}
                                max={100}
                                value={item.rate}
                                disabled />
                            </Stack>
                        )),
                        form: {
                            size: 0,
                            high: 5,
                            list: [
                                {
                                    name: 'item',
                                    size: 'tiny',
                                    text: 'Pregunta',
                                    cast: (item: any) => (
                                        <Stack
                                            gap={1}
                                            direction="row">
                                            <Stack direction="row">
                                                <Typography sx={(theme) => ({color: theme.palette.primary.main})}>
                                                    {list[item]?.item}.
                                                </Typography>
                                                <Typography sx={(theme) => ({color: alpha(theme.palette.primary.main, 0.6)})}>
                                                    {list[item]?.sort} 
                                                </Typography>
                                            </Stack>
                                            <Typography>
                                                {list[item]?.text}
                                            </Typography>
                                        </Stack>
                                    )
                                },
                                {
                                    type: 'menu',
                                    name: 'data',
                                    hint: 'Opciones',
                                    text: 'Respuesta',
                                    bind: 'La opción es requerida.',
                                    list: [{item: 1, text: 'Sí'}, {item: 2, text: 'No'}, {item: 3, text: 'No aplica'}],
                                    live: (data: any, form: any) => {
                                        setTimeout(() => {
                                            setData((last) => ({...last, form: {...last.form, ...form}}));
                                        }, 100);
                                    }
                                },
                                {
                                    type: 'menu',
                                    name: 'risk',
                                    hint: 'Opciones',
                                    text: 'Riesgo',
                                    bind: 'La opción es requerida.',
                                    list: [{item: 3, text: 'Alto'}, {item: 2, text: 'Medio'}, {item: 1, text: 'Bajo'}]
                                },
                                {
                                    type: 'text',
                                    name: 'note',
                                    text: 'Comentario'
                                },
                                {
                                    type: 'text',
                                    name: 'plan',
                                    text: 'Plan de acción'
                                }
                            ]
                        }
                    }
                ]
            }
        ), [session.heap?.firm, rate]);

        const navigate = useNavigate();

        const {setAlert, setTitle} = useApplication();

        useEffect(() => {
            Firms.load(0, 0, '', '', null, null, (done, data) => {
                if (done) {
                    setData((last) => ({
                        ...last,
                        heap: {
                            ...last.heap,
                            firm: {
                                ...last.heap.firm,
                                list: data.data?.map((next: any) => (
                                    {item: next.item, text: next.name}
                                ))
                            }
                        }
                    }));
                }
            });

            setTitle('Nuevo diagnóstico');
        }, []);

        return (
            <Grid
                direction="column"
                display="flex"
                spacing={2}
                container>
                <Grid item>
                    <Typography
                        color="text.primary"
                        variant="h4"
                        gutterBottom>
                        Nuevo diagnóstico
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de diagnósticos.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    <Form
                        view={view}
                        data={data.form}
                        fail={data.fail}
                        wait={data.wait}
                        lock={data.lock}
                        quit={{
                            text: 'Cancelar',
                            task: () => {
                                navigate('/diagnosticos');
                            }
                        }}
                        save={{
                            text: 'Guardar',
                            task: async (form: any) => {
                                setData((last) => ({...last, lock: true, form: form}));
                                
                                Store.make(form, (done: boolean, data: any) => {
                                    if (done) {
                                        navigate('/diagnosticos');

                                        setAlert((data?.text ?? 'The object was successfully created.'), 'success');
                                    } else {
                                        setData((last) => ({...last, lock: false, fail: data?.form ?? {}}));

                                        setAlert((data?.text ?? 'The object could not be created successfully.'), 'error');
                                    }
                                });
                            }
                        }}
                    />
                </Grid>
            </Grid>
        );
    }

    export const View = () => {
        const {setAlert, setTitle} = useApplication();

        const {session} = useSession();

        const navigate = useNavigate();

        const [data, setData] = useState<{wait: boolean, lock: boolean, fail: Hash<string>, heap: Hash<any>, form: Hash<any>}>({
            heap: {
                firm: {
                    wait: false,
                    fail: false,
                    take: 0,
                    size: 0,
                    list: []
                }
            },
            wait: false,
            lock: false,
            form: {},
            fail: {}
        });

        const rate = useMemo(() => (
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11].map((type) => {
                let bulk = data.form.list?.filter((next: any) => (((next.type == type) && ((next.data == 0) || (next.data == 1) || (next.data == 2))))) ?? [];

                let fine = bulk.filter((item: any) => (item.data == 1)) ?? [];
                
                return {type: type, name: list.find((item: any) => (item.item == type))?.text ?? null, fine: fine.length, rate: (bulk.length ? ((fine.length * 100) / bulk.length) : 0)};
            })
        ), [data.form.list]);

        const view:any = useMemo(() => ({
            data: [
                {
                    line: true,
                    name: 'Data',
                    text: 'Detalles',
                    hint: 'Detalles básicos.'
                },
                {
                    lock: true,
                    type: 'text',
                    size: 'full',
                    name: 'area',
                    text: 'Sector'
                },
                {
                    lock: true,
                    type: 'area',
                    size: 'full',
                    name: 'work',
                    text: 'Actividades'
                },
                {
                    line: true,
                    name: 'Test',
                    text: 'Preguntas',
                    hint: 'Cuestionario de diagnóstico'
                },
                {
                    tile: true,
                    grid: true,
                    type: 'text',
                    size: 'full',
                    name: 'list',
                    foot: (item: any) => (item.sort ? item : (
                        <Stack>
                            <Typography
                                sx={(theme) => ({color: theme.palette.primary.main})}
                                variant="h6">
                                {item.type}. {(item = rate.find((some: any) => (some.type == item.type)))?.name}
                            </Typography>
                            <Slider sx={(theme) => (
                                {
                                    borderRadius: 8,
                                    borderColor: 'currentColor',
                                    '& .MuiSlider-thumb': {
                                        height: 32,
                                        width: 32,
                                        backgroundImage: 'url(/images/icons/mark.svg)',
                                        backgroundRepeat: 'no-repeat',
                                        backgroundSize: '28px 28px',
                                        backgroundColor: 'transparent',
                                        backgroundPosition: 'center',
                                        boxShadow: 'none'
                                    },
                                    '& .MuiSlider-thumb::before': {
                                        display: 'none'
                                    },
                                    '& .MuiSlider-thumb::after': {
                                        display: 'none',
                                    },
                                    '& .MuiSlider-root.Mui-disabled': {
                                        color: theme.palette.primary.main
                                    },
                                    '& .MuiSlider-track': {
                                        display: 'none'
                                    },
                                    '& .MuiSlider-rail': {
                                        background: 'linear-gradient(to right, red, orange, yellow, green)',
                                        opacity: 1,
                                        height: 6
                                    },
                                    '& .MuiSlider-valueLabel': {
                                        background: theme.palette.primary.main
                                    }
                                }
                            )}
                            min={0}
                            max={100}
                            value={item.rate}
                            disabled />
                        </Stack>
                    )),
                    form: {
                        size: 0,
                        high: 5,
                        list: [
                            {
                                name: 'item',
                                size: 'tiny',
                                text: 'Pregunta',
                                cast: (item: any) => (
                                    <Stack
                                        gap={1}
                                        direction="row">
                                        <Stack direction="row">
                                            <Typography sx={(theme) => ({color: theme.palette.primary.main})}>
                                                {list[item]?.item}.
                                            </Typography>
                                            <Typography sx={(theme) => ({color: alpha(theme.palette.primary.main, 0.6)})}>
                                                {list[item]?.sort} 
                                            </Typography>
                                        </Stack>
                                        <Typography>
                                            {list[item]?.text}
                                        </Typography>
                                    </Stack>
                                )
                            },
                            {
                                name: 'note',
                                text: 'Comentario'
                            },
                            {
                                name: 'plan',
                                text: 'Plan de acción'
                            }
                        ]
                    }
                }
            ]
        }), [session.heap?.firm, session.type, rate]);

        const [done, setDone] =  useState(0);

        const {item} = useParams();

        useEffect(() => {
            Store.find(`${item}`, '', (done, item: any) => {
                setTimeout(() => {
                    if (done) {
                        setDone(1);

                        setData((last) => ({
                            ...last,
                            form: {
                                ...item,
                                list: list.map((data, next) => (
                                    {
                                        data: 0,
                                        risk: 0,
                                        note: '',
                                        plan: '',
                                        item: next,
                                        text: data.text,
                                        rate: data.rate,
                                        type: data.item,
                                        sort: data.sort,
                                        ...item?.list?.find((some: any) => (some.item == next))
                                    }
                                )),
                                file: [4, 5].includes(session.type) ? item.file : null
                            }
                        }));

                        setDone(1);
                    } else {
                        setDone(2);
                    }
                }, 300);
            });
        }, [item]);

        useEffect(() => {
            Firms.load(0, 0, '', '', null, null, (done, data) => {
                if (done) {
                    setData((last) => ({
                        ...last,
                        heap: {
                            ...last.heap,
                            firm: {
                                ...last.heap.firm,
                                wait: false,
                                list: data.data?.map((next: any) => ({item: next.item, text: next.name}))
                            }
                        }
                    }));
                } else {
                    setData((last) => ({
                        ...last,
                        heap: {
                            ...last.heap,
                            firm: {
                                ...last.heap.firm,
                                wait: false,
                                fail: true
                            }
                        }
                    }));
                }
            });

            setData((last) => ({
                ...last,
                heap: {
                    ...last.heap,
                    firm: {
                        ...last.heap.firm,
                        wait: true
                    }
                }
            }));

            setTitle('Detalles Diagnóstico');
        }, []);

        return (
            <Grid
                direction="column"
                display="flex"
                spacing={2}
                container>
                <Grid item>
                    <Typography
                        color="text.primary"
                        variant="h4"
                        gutterBottom>
                        Detalles Diagnóstico
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de diagnósticos.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    {(Boolean(done) ? (
                        ((done == 1) ? (
                            <Form
                                data={data.form}
                                fail={data.fail}
                                wait={data.wait}
                                lock={data.lock}
                                view={view}
                            />
                        ) : (
                            <Card sx={{padding: '128px 0px 64px 0px'}}>
                                <Box sx={{display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                    <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                        <SvgIcon sx={{width: '64px', height: '64px'}}>
                                            <g
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                stroke="#B3B3B3"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                                                <path d="M4 13h3l3 3h4l3-3h3"/>
                                            </g>
                                        </SvgIcon>
                                    </Avatar>
                                    <Typography
                                        color="text.primary"
                                        variant="h4"
                                        gutterBottom>
                                        No encontrado
                                    </Typography>
                                    <Typography
                                        color="text.secondary"
                                        gutterBottom>
                                        El objeto no pudo ser encontrado.
                                    </Typography>
                                </Box>
                            </Card>
                        ))
                    ) : (
                        <Box
                            justifyContent="center"
                            alignItems="center"
                            display="flex"
                            flex={1}>
                            <CircularProgress size={48} />
                        </Box>
                    ))}
                </Grid>
            </Grid>
        );
    }

    export const Edit = () => {
        const {setAlert, setTitle} = useApplication();

        const {session} = useSession();

        const navigate = useNavigate();

        const [data, setData] = useState<{wait: boolean, lock: boolean, fail: Hash<string>, heap: Hash<any>, form: Hash<any>}>({
            heap: {
                firm: {
                    wait: false,
                    fail: false,
                    take: 0,
                    size: 0,
                    list: []
                }
            },
            wait: false,
            lock: false,
            form: {},
            fail: {}
        });

        const rate = useMemo(() => (
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11].map((type) => {
                let bulk = data.form.list?.filter((next: any) => (((next.type == type) && ((next.data == 0) || (next.data == 1) || (next.data == 2))))) ?? [];

                let fine = bulk.filter((item: any) => (item.data == 1)) ?? [];
                
                return {type: type, name: list.find((item: any) => (item.item == type))?.text ?? null, fine: fine.length, rate: (bulk.length ? ((fine.length * 100) / bulk.length) : 0)};
            })
        ), [data.form.list]);

        const view:any = useMemo(() => ({
            data: [
                {
                    step: 0,
                    line: true,
                    name: 'Main',
                    text: 'Básica',
                    hint: 'Información básica'
                },
                {
                    type: 'list',
                    size: 'full',
                    name: 'bind',
                    text: 'Compañia',
                    bind: 'La opción es requerida.',
                    list: session.heap?.firm?.map((next: any) => ({item: next.item, text: next.name, hint: next.card})) ?? []
                },
                {
                    type: 'text',
                    size: 'full',
                    name: 'area',
                    text: 'Sector'
                },
                {
                    type: 'area',
                    size: 'full',
                    name: 'work',
                    text: 'Actividades'
                },
                {
                    type: 'file',
                    size: 'full',
                    name: 'file',
                    text: 'Reporte'
                },
                {
                    line: true,
                    name: 'Test',
                    text: 'Preguntas',
                    hint: 'Cuestionario de diagnóstico'
                },
                {
                    tile: true,
                    grid: true,
                    type: 'text',
                    size: 'full',
                    name: 'list',
                    foot: (item: any) => (item.sort ? item : (
                        <Stack>
                            <Typography
                                sx={(theme) => ({color: theme.palette.primary.main})}
                                variant="h6">
                                {item.type}. {(item = rate.find((some: any) => (some.type == item.type)))?.name}
                            </Typography>
                            <Slider sx={(theme) => (
                                {
                                    borderRadius: 8,
                                    borderColor: 'currentColor',
                                    '& .MuiSlider-thumb': {
                                        height: 32,
                                        width: 32,
                                        backgroundImage: 'url(/images/icons/mark.svg)',
                                        backgroundRepeat: 'no-repeat',
                                        backgroundSize: '28px 28px',
                                        backgroundColor: 'transparent',
                                        backgroundPosition: 'center',
                                        boxShadow: 'none'
                                    },
                                    '& .MuiSlider-thumb::before': {
                                        display: 'none'
                                    },
                                    '& .MuiSlider-thumb::after': {
                                        display: 'none',
                                    },
                                    '& .MuiSlider-root.Mui-disabled': {
                                        color: theme.palette.primary.main
                                    },
                                    '& .MuiSlider-track': {
                                        display: 'none'
                                    },
                                    '& .MuiSlider-rail': {
                                        background: 'linear-gradient(to right, red, orange, yellow, green)',
                                        opacity: 1,
                                        height: 6
                                    },
                                    '& .MuiSlider-valueLabel': {
                                        background: theme.palette.primary.main
                                    }
                                }
                            )}
                            min={0}
                            max={100}
                            value={item.rate}
                            disabled />
                        </Stack>
                    )),
                    form: {
                        size: 0,
                        high: 5,
                        list: [
                            {
                                name: 'item',
                                size: 'tiny',
                                text: 'Pregunta',
                                cast: (item: any) => (
                                    <Stack
                                        gap={1}
                                        direction="row">
                                        <Stack direction="row">
                                            <Typography sx={(theme) => ({color: theme.palette.primary.main})}>
                                                {list[item]?.item}.
                                            </Typography>
                                            <Typography sx={(theme) => ({color: alpha(theme.palette.primary.main, 0.6)})}>
                                                {list[item]?.sort} 
                                            </Typography>
                                        </Stack>
                                        <Typography>
                                            {list[item]?.text}
                                        </Typography>
                                    </Stack>
                                )
                            },
                            {
                                type: 'menu',
                                name: 'data',
                                hint: 'Opciones',
                                text: 'Respuesta',
                                bind: 'La opción es requerida.',
                                list: [{item: 1, text: 'Sí'}, {item: 2, text: 'No'}, {item: 3, text: 'No aplica'}],
                                live: async (data: any, form: any) => {
                                    setData((last) => ({...last, form: {...last.form, ...form}}));
                                }
                            },
                            {
                                type: 'menu',
                                name: 'risk',
                                hint: 'Opciones',
                                text: 'Riesgo',
                                bind: 'La opción es requerida.',
                                list: [{item: 3, text: 'Alto'}, {item: 2, text: 'Medio'}, {item: 1, text: 'Bajo'}]
                            },
                            {
                                type: 'text',
                                name: 'note',
                                text: 'Comentario'
                            },
                            {
                                type: 'text',
                                name: 'plan',
                                text: 'Plan de acción'
                            }
                        ]
                    }
                }
            ]
        }), [session.heap?.firm, session.type, rate]);

        const [done, setDone] =  useState(0);

        const {item} = useParams();

        useEffect(() => {
            Store.find(`${item}`, '', (done, item: any) => {
                setTimeout(() => {
                    if (done) {
                        setDone(1);

                        setData((last) => ({
                            ...last,
                            form: {
                                ...item,
                                list: list.map((data, next) => (
                                    {
                                        data: 0,
                                        risk: 0,
                                        note: '',
                                        plan: '',
                                        item: next,
                                        text: data.text,
                                        rate: data.rate,
                                        type: data.item,
                                        sort: data.sort,
                                        ...item?.list?.find((some: any) => (some.item == next))
                                    }
                                )),
                                file: [4, 5].includes(session.type) ? item.file : null
                            }
                        }));

                        setDone(1);
                    } else {
                        setDone(2);
                    }
                }, 300);
            });
        }, [item]);

        useEffect(() => {
            Firms.load(0, 0, '', '', null, null, (done, data) => {
                if (done) {
                    setData((last) => ({
                        ...last,
                        heap: {
                            ...last.heap,
                            firm: {
                                ...last.heap.firm,
                                wait: false,
                                list: data.data?.map((next: any) => ({item: next.item, text: next.name}))
                            }
                        }
                    }));
                } else {
                    setData((last) => ({
                        ...last,
                        heap: {
                            ...last.heap,
                            firm: {
                                ...last.heap.firm,
                                wait: false,
                                fail: true
                            }
                        }
                    }));
                }
            });

            setData((last) => ({
                ...last,
                heap: {
                    ...last.heap,
                    firm: {
                        ...last.heap.firm,
                        wait: true
                    }
                }
            }));

            setTitle('Editar Diagnóstico');
        }, []);

        return (
            <Grid
                direction="column"
                display="flex"
                spacing={2}
                container>
                <Grid item>
                    <Typography
                        color="text.primary"
                        variant="h4"
                        gutterBottom>
                        Editar Diagnóstico
                    </Typography>
                    <Typography
                        color="text.secondary"
                        gutterBottom>
                        Módulo de manejo de diagnósticos.
                    </Typography>
                </Grid>
                <Grid
                    display="flex"
                    flex={1}
                    item>
                    {(Boolean(done) ? (
                        ((done == 1) ? (
                            <Form
                                data={data.form}
                                fail={data.fail}
                                wait={data.wait}
                                lock={data.lock}
                                view={view}
                                quit={{
                                    text: 'Cancelar',
                                    task: () => {
                                        navigate('/diagnosticos');
                                    }
                                }}
                                save={{
                                    text: 'Guardar',
                                    task: (form: any) => {
                                        setData((last) => ({...last, lock: true, form: form}));
            
                                        Store.save(`${item}`, form, (done: boolean, data: any) => {
                                            if (done) {
                                                setData((last) => ({...last, lock: false}));

                                                navigate('/diagnosticos');

                                                setAlert((data?.text ?? 'The object was successfully updated.'), 'success');
                                            } else {
                                                setData((last) => ({...last, lock: false, fail: data?.form ?? {}}));

                                                setAlert((data?.text ?? 'The object could not be updated successfully.'), 'error');
                                            }
                                        });
                                    }
                                }}
                            />
                        ) : (
                            <Card sx={{padding: '128px 0px 64px 0px'}}>
                                <Box sx={{display: 'flex', flexDirection: 'column', alignItems: 'center'}}>
                                    <Avatar sx={{mb: 2, width: 96, height: 96, color: '#B3B3B3', background: '#F3F3F3'}}>
                                        <SvgIcon sx={{width: '64px', height: '64px'}}>
                                            <g
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                stroke="#B3B3B3"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M4 6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                                                <path d="M4 13h3l3 3h4l3-3h3"/>
                                            </g>
                                        </SvgIcon>
                                    </Avatar>
                                    <Typography
                                        color="text.primary"
                                        variant="h4"
                                        gutterBottom>
                                        No encontrado
                                    </Typography>
                                    <Typography
                                        color="text.secondary"
                                        gutterBottom>
                                        El objeto no pudo ser encontrado.
                                    </Typography>
                                </Box>
                            </Card>
                        ))
                    ) : (
                        <Box
                            justifyContent="center"
                            alignItems="center"
                            display="flex"
                            flex={1}>
                            <CircularProgress size={48} />
                        </Box>
                    ))}
                </Grid>
            </Grid>
        );
    }
}