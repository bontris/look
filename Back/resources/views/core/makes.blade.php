@extends('core')

@section('page')
    <div class="col-xxl-12 col-12">
        <div class="nftmax-body">
            <!-- Dashboard Inner -->
            <div class="nftmax-dsinner" v-if="same(view, 1)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">Listado de marcas</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
						<h3 class="nftmax-table__title mb-0">
                            Marcas <span class="nftmax-table__badge">@{{text(data.size)}}</span>
                        </h3>
						<div class="nftmax-header__amount" @click.stop="open('make')">
							<div class="nftmax-amount__icon">
                                <img src="assets/img/bag-icon.svg" alt="#">
                            </div>
							<div class="nftmax-amount__digit">
                                Crear
                            </div>
							<div class="nftmax-header__plus">
                                <img src="assets/img/plus-icon.svg" alt="#">
                            </div>
						</div>
                        <div class="nftmax-header__amount">
							<div class="nftmax-amount__icon">
                                <img src="assets/img/bag-icon.svg" alt="#">
                            </div>
							<div class="nftmax-amount__digit">
                                Cargar
                            </div>
							<div class="nftmax-header__plus">
                                <label style="margin: 0px 4px 0px 0px; cursor: pointer">
                                    <img src="assets/img/plus-icon.svg" alt="#">
                                    <input
                                        id="load"
                                        type="file"
                                        @change="open('bulk', $event.target)"
                                        accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                        hidden />
                                </lable>
                            </div>
						</div>
                        <div class="nftmax-header__amount" @click.stop="open('date')">
							<div class="nftmax-amount__icon">
                                <img src="assets/img/bag-icon.svg" alt="#">
                            </div>
							<div class="nftmax-amount__digit">
                                Reporte
                            </div>
							<div class="nftmax-header__plus">
                                <i class="fa-solid fa-search"></i>
                            </div>
						</div>
                        <ul  class="nav nav-tabs  nftmax-dropdown__list" id="nav-tab" role="tablist">
                            <li class="nav-item dropdown ">
                                <a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Tipo <span class="nftmax-table__arrow--icon"><svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></span></a>
                                <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                    <a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_1" role="tab">Pública</a>
									<a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_2" role="tab">Privada</a>
									<a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_3" role="tab">Mixta</a>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="table_1" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th class="nftmax-table__column-1 nftmax-table__h1">Marca</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Estado</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Tipo</th>
                                        <th width="140">&nbsp;</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr v-for="item in data.list">
                                        <td class="nftmax-table__column-1 nftmax-table__data-1">
                                            <div class="nftmax-table__product">
                                                <div class="nftmax-table__product-img">
                                                    <img :src="(item.icon ? `/images/${item.icon}` : '/assets/img/person.png')" alt="#">
                                                </div>
                                                <div class="nftmax-table__product-content">
                                                    <h4 class="nftmax-table__product-title">
                                                        @{{item.name}}
                                                    </h4>
                                                    <p class="nftmax-table__product-desc" v-if="item.number">
                                                        @{{item.number}}</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            @{{{1: 'Negada', 2: 'Publicada', 3: 'Registrada', 4: 'Bajo examen de fondo'}[item.rank] ?? '-- -- --'}}
                                        </td>
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status" :class="{1: 'nftmax-gbcolor', 2: 'nftmax-rbcolor', 3: 'nftmax-bbcolor'}[item.type]">@{{{1: 'Mixta', 2: 'Nominativa', 3: 'Figurativa', 4: '3D', 5: 'Sonido', 6: 'Tridimensional mixta'}[item.type]}}</div>
                                        </td>
                                        <td>
                                            <a @click="open('date', null, item)" class="nftmax-table__action_btn" title="Reporte">
                                                <i class="fa-solid fa-search"></i>
                                            </a>
                                            <a @click="open('edit', item)" class="nftmax-table__action_btn" title="Editar">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <a @click="open('drop', item)" class="nftmax-table__action_btn" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="nftmax-dsinner" v-if="same(view, 2)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">@{{(pick ? 'Actualizar marca' : 'Crear nueva marca')}}</h2>
                </div>
                <div class="nftmax__item">
                    <div class="nftmax__item-heading">
                        <h2 class="nftmax__item-title nftmax__item-title--psingle">Formularo</h2>
                        <p class="nftmax__item-text nftmax__item-text--single">Detalles de la marca.</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="nftmax__item-box">
                                <div class="row nftmax-pcolumn">
                                    <div class="col-xxl-5 col-lg-5 col-12 nftmax-pcolumn__one">
                                        <div class="nftmax__file-top">
                                            <div class="nftmax__file-upload mb-4">
                                                <div class="upload-files">
                                                    <div class="body" id="drop">
                                                        <img class="nftmax__file-upload--img" src="/assets/img/upload.png" alt="">
                                                        <p class="pointer-none nftmax__file-text">
                                                            <b>Logotipo</b>
                                                        </p>
                                                        <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered mt-4 bg radius">
                                                            <label for="load">Buscar imágen</label>
                                                            <input
                                                                id="load"
                                                                type="file"
                                                                @change="(form.icon = $event.target.files.item(0))"
                                                                accept="image/jpeg,image/png" />
                                                        </button>
                                                        <img class="p-4" style="width: 240px; height: 240px; border-radius: 50%" v-if="(form.icon ?? pick.icon)" :src="(form.icon ? path(form.icon) : `/images/${pick.icon}`)">
                                                        <p class="pt-2 mx-4">Sólo se admiten imágenes JPG ó PNG.</p>
                                                        <div style="color: red" v-if="fail?.list?.icon">@{{fail?.list?.icon}}</div>
                                                    </div>
                                                    <div class="nftmax__file-updated">
                                                        <div class="divider">
                                                            <span><a>FILES</a></span>
                                                        </div>
                                                        <div class="list-files"></div>
                                                        <button class="importar">UPDATE FILES</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-7 col-lg-7 col-12 nftmax-pcolumn__two">
                                        <div class="nftmax__item-form--main">
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Tipo</label>
                                                <select class="nftmax__item-select" v-model="form.type" required="required">
                                                    <option value="0">Seleccione una opción</option>
                                                    <option value="1">Mixta</option>
                                                    <option value="2">Nominativa</option>
                                                    <option value="3">Figurativa</option>
                                                    <option value="4">3D</option>
                                                    <option value="5">Sonido</option>
                                                    <option value="6">Tridimensional mixta</option>
                                                </select>
                                                <div style="color: red" v-if="fail?.list?.type">@{{fail?.list?.type}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Nombre</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Nombre de la marca" required="required" v-model="form.name">
                                                <div style="color: red" v-if="fail?.list?.name">@{{fail?.list?.name}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Eslogan</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Eslogan de la marca" required="required" v-model="form.text">
                                                <div style="color: red" v-if="fail?.list?.text">@{{fail?.list?.text}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Clases</label>
                                                <div
                                                    style="margin-bottom: 10px; display: flex; gap: 10px;"
                                                    v-for="item, slot in form.sort">
                                                    <input class="nftmax__item-input" type="text" placeholder="Código de la clase" required="required" v-model="form.sort[slot]">
                                                    <div style="align-items: center; display: flex; gap: 5px">
                                                        <button
                                                            class="nftmax-sidebar__button-btn nftmax-request_request"
                                                            @click="form.sort.splice(slot + 1, 0, '')"
														    :disabled="(same(form.sort.length, 20) || wait)">
                                                            <i class="fa-solid fa-add"></i>
                                                        </button>
                                                        <button
                                                            class="nftmax-sidebar__button-btn nftmax-request_close"
                                                            @click="form.sort.splice(form.sort.indexOf(item), 1)"
														    :disabled="(same(form.sort.length, 1) || wait)">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div style="color: red" v-if="fail?.list?.sort">@{{fail?.list?.sort}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Número del caso</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Número de caso" v-model="form.number">
                                                <div style="color: red" v-if="fail?.list?.number">@{{fail?.list?.number}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Título del caso</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Título de caso" v-model="form.title">
                                                <div style="color: red" v-if="fail?.list?.title">@{{fail?.list?.title}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Estado del caso</label>
                                                <select class="nftmax__item-select" v-model="form.rank">
                                                    <option value="0">Seleccione una opción</option>
                                                    <option value="1">Negada</option>
                                                    <option value="2">Publicada</option>
                                                    <option value="3">Registrada</option>
                                                    <option value="4">Bajo examen de fondo</option>
                                                </select>
                                                <div style="color: red" v-if="fail?.list?.rank">@{{fail?.list?.rank}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Fecha de radicación</label>
                                                <input class="nftmax__item-input" type="date" placeholder="dd/mm/aaaa" v-model="form.date">
                                                <div style="color: red" v-if="fail?.list?.date">@{{fail?.list?.date}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Vigencia</label>
                                                <input class="nftmax__item-input" type="date" placeholder="dd/mm/aaaa" v-model="form.validation">
                                                <div style="color: red" v-if="fail?.list?.validation">@{{fail?.list?.validation}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Referencia del solicitante</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Referencia del solicitante" v-model="form.reference">
                                                <div style="color: red" v-if="fail?.list?.reference">@{{fail?.list?.reference}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Titular</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Titular" v-model="form.titular">
                                                <div style="color: red" v-if="fail?.list?.titular">@{{fail?.list?.titular}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Descripción Productos y Servicios</label>
                                                <div
                                                    style="margin-bottom: 10px; display: flex; gap: 10px;"
                                                    v-for="item, slot in form.data">
                                                    <span>
                                                        <input class="nftmax__item-input" type="text" placeholder="Código" required="required" v-model="form.data[slot].code">
                                                        <div style="color: red" v-if="fail?.list[`data.${slot}.code`]">@{{fail?.list[`data.${slot}.code`]}}</div>
                                                     </span>
                                                     <span>
                                                        <input class="nftmax__item-input" type="text" placeholder="Descripción" required="required" v-model="form.data[slot].text">
                                                        <div style="color: red" v-if="fail?.list[`data.${slot}.text`]">@{{fail?.list[`data.${slot}.text`]}}</div>
                                                     </span>
                                                    <div style="align-items: center; display: flex; gap: 5px">
                                                        <button
                                                            class="nftmax-sidebar__button-btn nftmax-request_request"
                                                            @click="form.data.splice(slot + 1, 0, {code: null, text: null})"
														    :disabled="(same(form.sort.length, 20) || wait)">
                                                            <i class="fa-solid fa-add"></i>
                                                        </button>
                                                        <button
                                                            class="nftmax-sidebar__button-btn nftmax-request_close"
                                                            @click="form.data.splice(form.data.indexOf(item), 1)"
														    :disabled="(same(form.data.length, 1) || wait)">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Calendario de clasificación Niza</label>
                                                <select class="nftmax__item-select" v-model="form.schedule">
                                                    <option value="0">Seleccione una opción</option>
                                                    <option value="1">01</option>
                                                    <option value="2">02</option>
                                                    <option value="3">03</option>
                                                    <option value="4">04</option>
                                                    <option value="5">05</option>
                                                    <option value="6">06</option>
                                                    <option value="7">07</option>
                                                    <option value="8">08</option>
                                                    <option value="9">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select>
                                                <div style="color: red" v-if="fail?.list?.schedule">@{{fail?.list?.schedule}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Bajo oposición</label>
                                                <select class="nftmax__item-select" v-model="form.opposition">
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>
                                                <div style="color: red" v-if="fail?.list?.opposition">@{{fail?.list?.opposition}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Apoderado</label>
                                                <input class="nftmax__item-input" type="text" placeholder="Apoderado" v-model="form.proxy">
                                                <div style="color: red" v-if="fail?.list?.proxy">@{{fail?.list?.proxy}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Fecha de registro</label>
                                                <input class="nftmax__item-input" type="date" placeholder="dd/mm/aaaa" v-model="form.registration">
                                                <div style="color: red" v-if="fail?.list?.registration">@{{fail?.list?.registration}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Fecha de prioridad</label>
                                                <input class="nftmax__item-input" type="date" placeholder="dd/mm/aaaa" v-model="form.priority">
                                                <div style="color: red" v-if="fail?.list?.priority">@{{fail?.list?.priority}}</div>
                                            </div>
                                            <div class="nftmax__item-form--group">
                                                <label class="nftmax__item-label">Otra información</label>
                                                <textarea class="nftmax__item-input nftmax__item-textarea" placeholder="Otra información" v-model="form.information"></textarea>
                                                <div style="color: red" v-if="fail?.list?.information">@{{fail?.list?.information}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nftmax__item-button--group">
                                <button class="nftmax__item-button--single nftmax__item-button--cancel" data-bs-toggle="modal"  data-bs-target="#quit">Cancelar</button>
                                <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius nftmax-item__btn" data-bs-toggle="modal"  data-bs-target="#save">@{{(pick ? 'Actualizar marca' : 'Crear marca')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="nftmax-dsinner" v-if="same(view, 3)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">Reporte @{{pick?.name}}</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
						<h3 class="nftmax-table__title mb-0">
                            Resultados <span class="nftmax-table__badge">@{{text(seek?.length)}}</span>
                        </h3>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="table_1" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th style="text-align: left">Nombre</th>
                                        <th style="text-align: left; width: 140px">Expediente</th>
                                        <th style="text-align: left">Clases</th>
                                        <th style="text-align: center; width: 120px">Naturaleza</th>
                                        <th style="text-align: center; width: 120px">Estado</th>
                                        <th style="text-align: right; width: 120px">Fecha</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr v-for="item in seek" :style="`background: ${tone[item.rate]}; color: ${same(item.rate, 5, '#FFFFFF', '#374557')}`">
                                        <td style="text-align: left">
                                            @{{item.text}}
                                        </td>
                                        <td style="text-align: left">
                                            @{{item.card}}
                                        </td>
                                        <td style="text-align: left">
                                            @{{item.sort?.join(', ')}}
                                        </td>
                                        <td style="text-align: center">
                                            <div class="nftmax-table__status" :class="{1: 'nftmax-gbcolor', 2: 'nftmax-rbcolor', 3: 'nftmax-bbcolor'}[item.type]">@{{{1: 'Mixta', 2: 'Nominativa', 3: 'Figurativa', 4: '3D', 5: 'Sonido', 6: 'Tridimensional mixta'}[item.type]}}</div>
                                        </td>
                                        <td style="text-align: center">
                                            <div class="nftmax-table__status px-3" :class="{1: 'nftmax-gbcolor', 2: 'nftmax-rbcolor'}[item.rank]">@{{{1: 'Publicada'}[item.rank]}}</div>
                                        </td>
                                        <td style="text-align: right">
                                            @{{date(item.date, 'DD/MM/YYYY')}}
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                    </div>
                    <div class="nftmax__item-button--group">
                        <button class="nftmax__item-button--single nftmax__item-button--cancel" data-bs-toggle="modal"  data-bs-target="#exit">Cerrar</button>
                    </div>
                </div>
            </div>
            <div class="nftmax-dsinner" v-if="same(view, 4)">
                <div class="nftmax-inner__heading">
                    <h2 class="nftmax-inner__page-title">Reporte @{{pick?.name}}</h2>
                </div>
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
						<h3 class="nftmax-table__title mb-0">
                            Resultados <span class="nftmax-table__badge">@{{text(seek?.length)}}</span>
                        </h3>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="table_1" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th style="text-align: left">Marca</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <template v-for="make in seek">
                                        <tr>
                                            <td style="text-align: left">
                                                <h4>@{{make.name}}</h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0px">
                                                <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                                    <!-- NFTMax Table Head -->
                                                    <thead class="nftmax-table__head">
                                                        <tr>
                                                            <th style="padding-top: 15px; text-align: left">Nombre</th>
                                                            <th style="padding-top: 15px; text-align: left; width: 140px">Expediente</th>
                                                            <th style="padding-top: 15px; text-align: left">Clases</th>
                                                            <th style="padding-top: 15px; text-align: center; width: 120px">Naturaleza</th>
                                                            <th style="padding-top: 15px; text-align: center; width: 120px">Estado</th>
                                                            <th style="padding-top: 15px; text-align: right; width: 120px">Fecha</th>
                                                        </tr>
                                                    </thead>
                                                    <!-- NFTMax Table Body -->
                                                    <tbody class="nftmax-table__body">
                                                        <tr v-for="item in make.list" :style="`background: ${tone[item.rate]}; color: ${same(item.rate, 5, '#FFFFFF', '#374557')}`">
                                                            <td style="text-align: left">
                                                                @{{item.text}}
                                                            </td>
                                                            <td style="text-align: left">
                                                                @{{item.card}}
                                                            </td>
                                                            <td style="text-align: left">
                                                                @{{item.sort?.join(', ')}}
                                                            </td>
                                                            <td style="text-align: center">
                                                                <div class="nftmax-table__status" :class="{1: 'nftmax-gbcolor', 2: 'nftmax-rbcolor', 3: 'nftmax-bbcolor'}[item.type]">@{{{1: 'Mixta', 2: 'Nominativa', 3: 'Figurativa', 4: '3D', 5: 'Sonido', 6: 'Tridimensional mixta'}[item.type]}}</div>
                                                            </td>
                                                            <td style="text-align: center">
                                                                <div class="nftmax-table__status px-3" :class="{1: 'nftmax-gbcolor', 2: 'nftmax-rbcolor'}[item.rank]">@{{{1: 'Publicada'}[item.rank]}}</div>
                                                            </td>
                                                            <td style="text-align: right">
                                                                @{{date(item.date, 'DD/MM/YYYY')}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <!-- End NFTMax Table Body -->
                                                </table>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                    </div>
                    <div class="nftmax__item-button--group">
                        <button class="nftmax__item-button--single nftmax__item-button--cancel" data-bs-toggle="modal"  data-bs-target="#exit">Cerrar</button>
                    </div>
                </div>
            </div>
            <!-- End Dashboard Inner -->

            <div class="nftmax-preview__modal modal" id="seek" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Reporte</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close" v-if="wait">
                                <div class="nftmax-preview__close-img"><img src="/assets/img/timer.png" alt="#"></div>
								<h2 class="nftmax-preview__close-title">Cargando información...</h2>
                            </div>
                            <div class="nftmax-preview__close" v-else>
                                <h2 class="nftmax-preview__close-title mb-4" v-if="quit">No se encontraron coincidencias</h2>
                                <select class="nftmax__item-select" v-model="item" style="width: 100%" v-else required>
                                    <option value="0">Seleccione una gaceta</option>
                                    <option v-for="item in list" :value="item.item">@{{item.post}} (@{{date(item.date, 'DD/MM/YYYY')}})</option>
                                </select>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="open('seek', pick)" :disabled="(wait || none(item))">
                                        Consultar
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Cancelar</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="quit" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Está seguro de que quiere salir del formulario?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius"  data-bs-dismiss="modal" @click="(view = 1)">
                                        Sí, salir
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="exit" tabindex="-1" aria-labelledby="CancelModalLabel" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Está seguro de que quiere salir del reporte?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius"  data-bs-dismiss="modal" @click="(view = 1)">
                                        Sí, salir
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="save" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">@{{(pick ? '¿Desea actualizar la marca?' : '¿Desea crear la nueva marca?')}}</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="save(form, pick)" :disabled="wait">
                                        @{{(wait ? (pick ? 'Actualizando...' : 'Creando...') : (pick ? 'Sí, actualizar' : 'Sí, crear'))}}
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nftmax-preview__modal modal fade" id="drop" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog  nftmax-close__modal-close">
                    <div class="modal-content nftmax-preview__modal-content">
                        <div class="modal-header nftmax__modal__header">
                            <h4 class="modal-title nftmax-preview__modal-title" id="CancelModalLabel">Confirmar</h4>
                        </div>
                        <div class="modal-body nftmax-modal__body modal-body nftmax-close__body">
                            <div class="nftmax-preview__close">
                                <div class="nftmax-preview__close-img"><img src="assets/img/close.png" alt="#"></div>
                                <h2 class="nftmax-preview__close-title">¿Desea eliminar la marca?</h2>
                                <div class="nftmax__item-button--group">
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius" @click="drop(pick)" :disabled="wait">
                                        @{{(wait ? 'Eliminando...' : 'Sí, eliminar')}}
                                    </button>
                                    <button class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered--plus radius" :disabled="wait">
                                        <span class="ntfmax__btn-textgr" data-bs-dismiss="modal" aria-label="Cerrar">Ahora no</span> 
                                    </button>																
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('code')
    <script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
			  		wait: false,
			  		menu: false,
                    quit: false,
			  		snap: null,
			  		pick: null,
			  		sync: null,
                    item: null,
                    seek: [],
                    list: [],
					view: 1,
			  		take: 32,
			  		high: 0,
			  		page: 0,
                    tone: {
                        5: '#E53935',
                        4: '#E65100',
                        3: '#FF8F00',
                        2: '#FDD835',
                        1: '#FFFFFF'
                    },
			  		data: {!!json_encode($data)!!},
					pile: {
						role: {
							wait: false,
							text: null,
							list: []
						}
					},
			  		show: {
			  			form: false,
			  			view: false,
			  			mail: false,
			  			crop: false,
			  			pass: false,
			  			wipe: false,
			  			lock: false,
			  			drop: false
			  		},
			  		fail: {
			  			text: null,
			  		    list: {}
			  		},
			  		sort: {
			  			text: null,
			  			type: null
			  		},
					bulk: {
						show: false,
						list: [],
						data: {
							note: null
						}
					},
			  		form: {
                        lock: 0,
                        rank: 0,
                        type: 0,
                        sort: [],
                        data: [],
                        icon: null,
                        date: null,
			  			name: null,
			  			text: null,
                        proxy: null,
                        title: null,
                        number: null,
                        titular: null,
                        priority: null,
                        schedule: null,
                        reference: null,
                        validation: null,
                        opposition: null,
                        information: null,
                        registration: null
			  		}
			    },
			    watch: {
					'pile.role.text': (text) => {
						if (self.pile.role.time) {
							clearTimeout(self.pile.role.time);
						}

						
					},
			    	sort: {
			    		handler: function (sort) {
			    		   	if (self.time) {
				    			clearTimeout(self.time);
				    		}

				    		self.time = setTimeout(function () {
				    			self.load(self.take, null, sort.text, sort.type);
				    		}, 500);
					    },
					    deep: true
			    	},
			    	data: {
			    		handler: function (data) {
			    		   self.load((self.take = data.itemsPerPage), (self.page = data.page), self.sort.text, self.sort.type);
					    },
					    deep: true
			    	}
			    },
			    methods: {
			    	load: function (take, page, text, type, done) {
			    		axios.get("{{route('makes', ['task' => 'load'])}}?take=" + (take ?? '') + '&page=' + (page ?? '') +
			    			                                                                      '&find=' + (text ?? '') +
			    			                                                                      '&type=' + (type ?? ''), {})
				             .then(function (data) {
				            self.list = data.data.data || [];

				            self.high = data.data.high || 0;

				            self.page = data.data.page || 0;

				            self.take = data.data.take || 0;

				            self.wait = false;

				            if (done) {
				            	done(false);
				            }
				        }).catch(function (fail) {
				            self.wait = false;

				            if (done) {
				            	done(true);
				            }
				        });

				        this.wait = true;
			    	},
			    	open: function (task, item, data) {
			    		self.note = {show: false,
	                                 type: null,
	                                 text: null};

			    		self.fail = {text: null,
			    			         list: {}};

			    		if (item) {
			    			switch (task) {
			    				case 'edit':
			    				    axios.get(`{{route('makes', ['task' => 'load'])}}/${item.hash}`, {})
							             .then(function (data) {
							            self.form = {
                                            icon: null,
                                            lock: data.data.lock,
                                            rank: data.data.rank,
                                            type: data.data.type,
                                            sort: data.data.sort ?? [''],
                                            data: data.data.data ?? [{code: null, text: null}],
                                            code: data.data.code,
                                            name: data.data.name,
                                            text: data.data.text,
                                            date: data.data.date,
                                            proxy: data.data.proxy,
                                            title: data.data.title,
                                            number: data.data.number,
                                            titular: data.data.titular,
                                            priority: data.data.priority,
                                            schedule: data.data.schedule,
                                            reference: data.data.reference,
                                            validation: data.data.validation,
                                            opposition: data.data.opposition,
                                            desription: data.data.desription,
                                            information: data.data.information,
                                            registration: data.data.registration
                                        };

										if (self.wait) {
											setTimeout(function () {
												self.wait = false;
											}, 200);
										} else {
											clearTimeout(self.time);
										}

										self.view = 2;
							        }).catch(function (fail) {
							            self.wait = false;

							            if (fail.response?.data?.text) {
			                            	self.note = {
                                                show: true,
			                            	    type: 'fail',
			                            	    text: fail.response.data.text
                                            };
			                            } else {
			                            	self.note = {
                                                show: true,
			                            	    type: 'fail',
			                            	    text: 'Se presentó un error inesperado.'
                                            };
			                            }
							        });

									self.time = setTimeout(function () {
										self.wait = true;
									}, 100);

							        self.pick = item;
			    					break;
                                case 'seek':
                                    axios.get(`{{route('makes', ['task' => 'seek'])}}/${item.hash}?load=${self.item}`, {})
							             .then(function (data) {
                                        if (self.same(self.none(data.data), false)) {
                                            self.seek = data.data;

                                            self.view = 3;
                                        }

                                        setTimeout(() => {
                                            if (self.same(self.none(data.data), false)) {
                                                $('#seek').modal('hide');
                                            }

                                            self.wait = false;

                                            self.quit = true;
                                        }, 200);
							        }).catch(function (fail) {
							            self.wait = false;

							            if (fail.response?.data?.text) {
			                            	self.note = {
                                                show: true,
			                            	    type: 'fail',
			                            	    text: fail.response.data.text
                                            };
			                            } else {
			                            	self.note = {
                                                show: true,
			                            	    type: 'fail',
			                            	    text: 'Se presentó un error inesperado.'
                                            };
			                            }
							        });

                                    self.wait = true;
			    					break;
                                case 'drop':
                                    $('#drop').modal('show');

                                    self.pick = item;
                                    break;
			    			}
			    		} else {
			    			switch (task) {
                                case 'seek':
                                    axios.get(`{{route('makes', ['task' => 'seek'])}}?load=${self.item}`, {})
							             .then(function (data) {
                                        if (self.same(self.none(data.data), false)) {
                                            self.seek = data.data;

                                            self.view = 4;
                                        }

                                        setTimeout(() => {
                                            if (self.same(self.none(data.data), false)) {
                                                $('#seek').modal('hide');
                                            }

                                            self.wait = false;

                                            self.quit = true;
                                        }, 200);
							        }).catch(function (fail) {
							            self.wait = false;

							            if (fail.response?.data?.text) {
			                            	self.note = {
                                                show: true,
			                            	    type: 'fail',
			                            	    text: fail.response.data.text
                                            };
			                            } else {
			                            	self.note = {
                                                show: true,
			                            	    type: 'fail',
			                            	    text: 'Se presentó un error inesperado.'
                                            };
			                            }
							        });

                                    $('#seek').modal('show');

							        self.quit = false;

                                    self.wait = true;
			    					break;
                                case 'date':
                                    axios.get(`{{route('makes', ['task' => 'date'])}}`, {})
                                            .then(function (data) {
                                        setTimeout(() => {
                                            self.list = data.data.data;

                                            self.wait = false;
                                        }, 200);
                                    }).catch(function (fail) {
                                        self.wait = false;

                                        if (fail.response?.data?.text) {
                                            self.note = {
                                                show: true,
                                                type: 'fail',
                                                text: fail.response.data.text
                                            };
                                        } else {
                                            self.note = {
                                                show: true,
                                                type: 'fail',
                                                text: 'Se presentó un error inesperado.'
                                            };
                                        }
                                    });

                                    $('#seek').modal('show');

                                    self.quit = false;

                                    self.pick = data;

                                    self.wait = true;

                                    self.item = 0;
                                    break;
			    				case 'make':
			    					self.form = {lock: 0,
                                                 type: 0,
                                                 rank: 0,
                                                 
                                                 sort: [
                                                    ''
                                                 ],
                                                 data: [
                                                    {code: null, text: null}
                                                 ],
                                                 icon: null,
                                                 date: null,
                                                 name: null,
                                                 text: null,
                                                 proxy: null,
                                                 title: null,
                                                 number: null,
                                                 titular: null,
                                                 priority: null,
                                                 schedule: null,
                                                 reference: null,
                                                 validation: null,
                                                 opposition: null,
                                                 information: null,
                                                 registration: null};

					             	self.pick = null;

									self.view = 2;
			    					break;
			    			}
			    		}
			    	},
			    	save: function (form, item) {
                        var data = new FormData();

                        self.fail = {text: null,
			    			         list: {}};

                        form.sort.forEach((item) => {
                            if ((item = parseInt(item))) {
                                data.append('sort[]', item);
                            }
                        })

                        form.data.forEach((item, next) => {console.log('data',data,item)
                            if ((item.code ?? item.text)) {
                                data.append(`data[${next}][code]`, item.code);
                                data.append(`data[${next}][text]`, item.text);
                            }
                        })

                        data.append('icon', form.icon ?? '');

                        data.append('type', (form.type ?? 0));

                        data.append('rank', (form.rank ?? 0));

		    		    data.append('name', (form.name ?? ''));

		    	        data.append('text', (form.text ?? ''));

                        data.append('date', (form.date ?? ''));

                        data.append('proxy', (form.proxy ?? ''));

                        data.append('title', (form.title ?? ''));

                        data.append('number', (form.number ?? ''));

                        data.append('titular', (form.titular ?? ''));

                        data.append('priority', (form.priority ?? ''));

                        data.append('schedule', (form.schedule ?? ''));

                        data.append('reference', (form.reference ?? ''));

                        data.append('validation', (form.validation ?? ''));

                        data.append('opposition', (form.opposition ?? ''));

                        data.append('information', (form.information ?? ''));

                        data.append('registration', (form.registration ?? ''));

                        if (item) {
				    		axios.post(`{{route('makes', ['task' => 'save'])}}/${item.hash}`, data, {'X-CSRF-TOKEN': '{{csrf_token()}}', 'Content-Type': 'multipart/form-data'})
	                             .then(function (data) {
                                $('#save').modal('hide');

                                item.sort = data.data.sort;

                                item.rank = form.rank;

                                item.type = form.type;

                                item.card = form.card;

                                item.name = form.name;

                                item.text = form.text;

	                            self.wait = false;

                                self.view = 1;
								
	                            self.note = {
                                    show: true,
                                	type: 'done',
                                	text: data.data.text
                                };
	                        })
	                        .catch(function (fail) {
	                            $('#save').modal('hide');
                                
                                self.wait = false;

                                if (fail.response?.data?.text) {
                                    self.fail = {text: fail.response.data.text,
                                                 list: fail.response.data.list ?? {}};
                                } else {
                                    self.fail.text = 'Se presentó un error inesperado.';
                                }
	                        });
				    	} else {
                            axios.post("{{route('makes', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
                                $('#save').modal('hide');

                                self.data.list.unshift({
                                    item: data.data.item,
                                    hash: data.data.hash,
                                    code: data.data.code,
                                    icon: data.data.icon,
                                    tone: data.data.tone,
                                    sort: data.data.sort,
                                    rank: form.rank,
                                    type: form.type,
                                    name: form.name,
                                    text: form.text
                                });

                                self.data.size = self.data.size + 1;

                                self.note = {show: true,
                                             type: 'done',
                                             text: data.data.text};

                                self.wait = false;

                                self.view = 1;
                            })
                            .catch(function (fail) {
                                $('#save').modal('hide');
                                
                                self.wait = false;

                                if (fail.response?.data?.text) {
                                    self.fail = {text: fail.response.data.text,
                                                 list: fail.response.data.list ?? {}};
                                } else {
                                    self.fail.text = 'Se presentó un error inesperado.';
                                }
                            });
                        }

				    	self.wait = true;
			    	},
                    drop: function (item) {
			    		axios.get(`{{route('makes', ['task' => 'drop'])}}/${item.hash}`, {})
				             .then(function (data) {
                            $('#drop').modal('hide');
                            self.wait = false;

                            self.show.drop = false;

                            self.data.size = self.data.size - 1;

                            self.data.list.splice(self.data.list.indexOf(item), 1);
				        }).catch(function (fail) {
				            self.wait = false;

				            if (fail.response?.data?.text) {
                                self.fail = {text: fail.response.data.text,
                                             list: fail.response.data.list ?? {}};
                            } else {
                                self.fail.text = 'Se presentó un error inesperado.';
                            }
				        });

				        self.wait = true;
			    	},
                    path: function (file) {
                        return URL.createObjectURL(file);
                    }
			    },
			    mounted: function () {
			    	setTimeout(function () {
	             		self.done = true;
	             	}, 500);
			    }
			})
  		});
  	</script>
@stop