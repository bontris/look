@extends('core')

@section('page')
    <div class="col-xxl-9 col-12 nftmax-main__column">
        <div class="nftmax-body">
            <!-- Dashboard Inner -->
            <div class="nftmax-dsinner">
                <!-- Dashboard Slider -->
                <div class="dashboard-banner nftmax-bg-cover mg-top-40" style="background-image:url('/assets/img/banner-bg.png')">
                    <div class="row">
                        <div class="col-12">
                            <div class="dashboard-banner__main" style="margin-left: 100px;">
                                <div class="dashboard-banner__column dashboard-banner__column--two">
                                    <div class="dashboard-banner__slider">
                                    @foreach($sliderBanner as $sliderBanner)
                                        <div class="dashboard-banner__single-slider">
                                            <img src="{{ asset($sliderBanner) }}" alt="#">
                                        </div>
                                    @endforeach   
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
                <!-- End Dashboard Slider -->
                
                
                
                <!-- Trending Action -->
                <div class="trending-action mg-top-40">
                    <h2 class="trending-action__heading">Servicios</h2>
                    <div class="row">
                        <div class="col-12">
                            <div class="trending-action__slider">
                                <!-- Treadning Single -->
                                @foreach($TrendingAction as $TrendingAction)
                                <div class="trending-action__single">
                                    <!-- Trending Head -->
                                    <div class="trending-action__head">
                                        <div class="trending-action__button">
                                            <a class="trending-action__btn heart-icon"><i class="fa-solid fa-heart"></i></a>
                                            <a class="trending-action__btn"><i class="fa-solid fa-ellipsis-vertical"></i></a>
                                        </div>
                                        <img src="{{asset($TrendingAction['0'])}}" alt="#">
                                    </div>
                                    <!-- Trending Body -->
                                    <div class="trending-action__body">
                                        <div class="trending-action__author-meta">
                                            <div class="trending-action__author-img"><img src="{{asset($TrendingAction['1'])}}" alt="#"></div>
                                            <p class="trending-action__author-name">Owned by <a href="profile.html">{{ $TrendingAction['2'] }}</a></p>
                                        </div>
                                        <h2 class="trending-action__title"><a href="active-bids.html">{{ $TrendingAction['3'] }}</a></h2>
                                        
                                    </div>
                                    <div class="dashboard-banner__button trending-action__bottom">
                                        <a href="{{url('/active-bids')}}" class="nftmax-btn nftmax-btn__secondary radius">Place a Bid</a>
                                        
                                    </div>
                                </div>
                                @endforeach
                                <!-- End Treadning Single -->
                                <!-- End Treadning Single -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Trending Action -->
                
                <div class="row nftmax-gap-30">
                    <div class="col-lg-6 col-md-6 col-12 nftmax-sixth-one">
                        <!-- Charts One -->
                        <div class="charts-main charts-home-one mg-top-40">
                            <div class="charts-main__heading">
                                <h4 class="charts-main__title">Sell History</h4>
                                <div class="charts-main__middle">
                                    <div class="charts-main__middle-single">
                                        <p class="charts-main__middle-text">Avg: Sell Price</p>
                                    </div>
                                    <div class="charts-main__middle-single">
                                        <p class="charts-main__middle-text nftmax-total__sales">Total Sell</p>
                                    </div>
                                </div>
                                
                                <div class="nftmax-chart__dropdown">
                                    <span class="nftmax-current">Current Week</span>
                                </div>
                            </div>
                            <div class="charts-main__one">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="chart__sell" role="tabpanel" aria-labelledby="chart__sell">
                                        <canvas id="myChart_one"></canvas>
                                    </div>
                                    <div class="tab-pane fade" id="chart__sell" role="tabpanel" aria-labelledby="chart__sell">
                                        <canvas id="myChart_one"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Charts One -->
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 nftmax-sixth-two">
                        <!-- Charts Two -->
                        <div class="charts-main charts-home-one mg-top-40">
                            <div class="charts-main__heading">
                                <h4 class="charts-main__title">Market Visitor</h4>
                                <div class="nftmax-chart__dropdown">
                                    <ul  class="nav nav-tabs nftmax-dropdown__list" id="nav-tab" role="tablist">
                                        <li class="nav-item dropdown">
                                            <a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Last 30 days <svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></a>
                                            <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                                <a class="list-group-item" data-bs-toggle="list" data-bs-target="#chart__visitor" role="tab">Last 15 Days</a>
                                                <a class="list-group-item" data-bs-toggle="list" data-bs-target="#chart__visitor_weekly" role="tab">Last 7 Days</a>
                                                <a class="list-group-item"  data-bs-toggle="list" data-bs-target="#chart__visitor_monthly" role="tab">Last Month</a>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="charts-main__one">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="chart__visitor" role="tabpanel" aria-labelledby="chart__visitor">
                                        <canvas id="myChart_two"></canvas>
                                    </div>
                                    <div class="tab-pane fade" id="chart__visitor_monthly" role="tabpanel" aria-labelledby="chart__visitor">
                                        <canvas id="myChart_two_monthly"></canvas>
                                    </div>
                                    <div class="tab-pane fade" id="chart__visitor_weekly" role="tabpanel" aria-labelledby="chart__visitor">
                                        <canvas id="myChart_two_weekly"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Charts Two -->
                    </div>
                </div>
                
                
                
                <div class="nftmax-table mg-top-40">
                    <div class="nftmax-table__heading">
                        <h3 class="nftmax-table__title mb-0">Tareas <span class="nftmax-table__badge p-2">@{{text(task.size)}}</span></h3>
                        <ul  class="nav nav-tabs  nftmax-dropdown__list" id="nav-tab" role="tablist">
                            <li class="nav-item dropdown ">
                                <a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Categorías <span class="nftmax-table__arrow--icon"><svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></span></a>
                                <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                    <a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_1" role="tab">Categoría 1</a>
                                    <a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_2" role="tab">Categoría 2</a>
                                    <a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_3" role="tab">Categoría 3</a>
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
                                        <th class="nftmax-table__column-1 nftmax-table__h1">Tarea</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Horas</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Fecha</th>
                                        <th class="nftmax-table__column-7 nftmax-table__h7">Estado</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                    <tr v-for="item in task.list">
                                        <td class="nftmax-table__column-1 nftmax-table__data-1">
                                            <div class="nftmax-table__product">
                                                <div class="nftmax-table__product-content">
                                                    <h4 class="nftmax-table__product-title">@{{item.name}}</h4>
                                                    <p class="nftmax-table__product-desc">Creada por  <a href="#">@{{item.head.name}}</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-6 nftmax-table__data-6">
                                            <p class="nftmax-table__text nftmax-table__time">@{{date(item.time)}}</p>
                                        </td>
                                        <td class="nftmax-table__column-6 nftmax-table__data-6">
                                            <p class="nftmax-table__text nftmax-table__time">@{{date(item.made, 'DD/MM/YYYY')}}</p>
                                        </td>
                                        <!-- nftmax-gbcolor -->
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status nftmax-sbcolor">Pendiente</div>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                        <div class="tab-pane fade" id="table_2" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th class="nftmax-table__column-1 nftmax-table__h1">All Products</th>
                                        <th class="nftmax-table__column-2 nftmax-table__h2">Value</th>
                                        <th class="nftmax-table__column-3 nftmax-table__h3">USD</th>
                                        <th class="nftmax-table__column-4 nftmax-table__h4">24H%</th>
                                        <th class="nftmax-table__column-5 nftmax-table__h5">Bids</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Time</th>
                                        <th class="nftmax-table__column-7 nftmax-table__h7">Status</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                @foreach($AllNFTSUpdateV2 as $user)
                                    <tr>
                                        <td class="nftmax-table__column-1 nftmax-table__data-1">
                                            <div class="nftmax-table__product">
                                                <div class="nftmax-table__product-img">
                                                    <img src="{{asset($user['0'])}}" alt="#">
                                                </div>
                                                <div class="nftmax-table__product-content">
                                                    <h4 class="nftmax-table__product-title">{{ $user['1'] }}</h4>
                                                    <p class="nftmax-table__product-desc">Owned by  <a href="#">{{ $user['2'] }}</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-2 nftmax-table__data-2">
                                            <div class="nftmax-table__amount nftmax-table__text-one">
                                                <img src="/assets/img/eth-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['3'] }}</span>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-3 nftmax-table__data-3">
                                            <div class="nftmax-table__amount nftmax-table__text-two">
                                                <img src="/assets/img/usd-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['4'] }}$</span>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-4 nftmax-table__data-4">
                                            <p class="nftmax-table__text nftmax-table__up-down nftmax-rcolor">{{ $user['5'] }}</p>
                                        </td>
                                        <td class="nftmax-table__column-5 nftmax-table__data-5">
                                            <p class="nftmax-table__text nftmax-table__bid-text">{{ $user['6'] }}</p>
                                        </td>
                                        <td class="nftmax-table__column-6 nftmax-table__data-6">
                                            <p class="nftmax-table__text nftmax-table__time">{{ $user['7'] }}</p>
                                        </td>
                                        @if($user['8']==1)
                                            <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status nftmax-sbcolor">Active</div>
                                            </td>
                                        @else
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status nftmax-gbcolor">Completed</div>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                        <div class="tab-pane fade" id="table_3" role="tabpanel" aria-labelledby="table_1">
                            <!-- NFTMax Table -->
                            <table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main-v1">
                                <!-- NFTMax Table Head -->
                                <thead class="nftmax-table__head">
                                    <tr>
                                        <th class="nftmax-table__column-1 nftmax-table__h1">All Products</th>
                                        <th class="nftmax-table__column-2 nftmax-table__h2">Value</th>
                                        <th class="nftmax-table__column-3 nftmax-table__h3">USD</th>
                                        <th class="nftmax-table__column-4 nftmax-table__h4">24H%</th>
                                        <th class="nftmax-table__column-5 nftmax-table__h5">Bids</th>
                                        <th class="nftmax-table__column-6 nftmax-table__h6">Time</th>
                                        <th class="nftmax-table__column-7 nftmax-table__h7">Status</th>
                                    </tr>
                                </thead>
                                <!-- NFTMax Table Body -->
                                <tbody class="nftmax-table__body">
                                @foreach($AllNFTSUpdateV3 as $user)
                                    <tr>
                                        <td class="nftmax-table__column-1 nftmax-table__data-1">
                                            <div class="nftmax-table__product">
                                                <div class="nftmax-table__product-img">
                                                    <img src="{{asset($user['0'])}}" alt="#">
                                                </div>
                                                <div class="nftmax-table__product-content">
                                                    <h4 class="nftmax-table__product-title">{{ $user['1'] }}</h4>
                                                    <p class="nftmax-table__product-desc">Owned by  <a href="#">{{ $user['2'] }}</a></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-2 nftmax-table__data-2">
                                            <div class="nftmax-table__amount nftmax-table__text-one">
                                                <img src="/assets/img/eth-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['3'] }}</span>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-3 nftmax-table__data-3">
                                            <div class="nftmax-table__amount nftmax-table__text-two">
                                                <img src="/assets/img/usd-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['4'] }}$</span>
                                            </div>
                                        </td>
                                        <td class="nftmax-table__column-4 nftmax-table__data-4">
                                            <p class="nftmax-table__text nftmax-table__up-down nftmax-rcolor">{{ $user['5'] }}</p>
                                        </td>
                                        <td class="nftmax-table__column-5 nftmax-table__data-5">
                                            <p class="nftmax-table__text nftmax-table__bid-text">{{ $user['6'] }}</p>
                                        </td>
                                        <td class="nftmax-table__column-6 nftmax-table__data-6">
                                            <p class="nftmax-table__text nftmax-table__time">{{ $user['7'] }}</p>
                                        </td>
                                        @if($user['8']==1)
                                            <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status nftmax-sbcolor">Active</div>
                                            </td>
                                        @else
                                        <td class="nftmax-table__column-7 nftmax-table__data-7">
                                            <div class="nftmax-table__status nftmax-gbcolor">Completed</div>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!-- End NFTMax Table Body -->
                            </table>
                            <!-- End NFTMax Table -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Dashboard Inner -->
        </div>
    </div>
    <div class="col-xxl-3 col-12 nftmax-main__sidebar">
        <div class="nftmax-sidebar mg-top-40">
            <div class="row">
				@if (isset($card))
					<div class="col-xxl-12 col-xl-6 col-12 nftmax-sidebar__widget">
					<div class="trending-action__single">
									<!-- Trending Head -->
									<div class="trending-action__head">
										<div class="trending-action__button v2">
											<a class="trending-action__btn heart-icon"><i class="fa-solid fa-heart"></i></a>
										</div>
										<img src="{{$card['icon']}}" alt="#">
									</div>
									<!-- Trending Body -->
									<div align="center" class="trending-action__body trending-marketplace__body" >
										<h2 class="trending-action__title">{{$card['name']}} {{$card['last']}}</h2>
										<p class="welcome-cta__text">Me gencanta bailar tango, mi perro se llama nacho como la comida y vivo en Concordia(FFF)</p><br>
										<div class="nftmax-currency" style="justify-content: center;">
											<div align="center" class="nftmax-currency__main" style="justify-content: center;">
												<div class="nftmax-currency__icon"><a target="_blank" href="mailto:{{$card['mail']}}"><img src="http://tallerapp.test/assets/img/ta1.png" alt="#"></a></div>
												<div class="nftmax-currency__icon"><a target="_blank" href="tel:{{$card['work']}}"><img src="http://tallerapp.test/assets/img/ta2.png" alt="#"></a></div>
												<div class="nftmax-currency__icon"><a target="_blank" href="https://api.whatsapp.com/send?phone=57{{$card['work']}}&text="><img src="http://tallerapp.test/assets/img/ta3.png" alt="#"></a></div>
											</div>
											
										</div>
									</div>
								</div>
					</div>
				@endif
                
                <div class="col-xxl-12 col-xl-6 col-12 nftmax-sidebar__widget">	
                    <!-- NFTMax Single Sidebar -->
                    <div class="nftmax-sidebar__single">
                        <ul class="nav nav-tabs nftmax-dropdown__list" id="nav-tab" role="tablist">
                            <li class="nav-item dropdown nftmax-multiple__adropdownn">
                                <a class="nftmax-heading__amount-dropdown nav-link  dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><div class="nftmax__amount-dropdown"><img src="/assets/img/eth-icon.png" alt="#">ETH rate<span class="nftmax-sidebar__arrow--icon"><svg width="14" height="7" viewBox="0 0 14 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.7092 0.288658C13.6163 0.197192 13.5057 0.124593 13.3839 0.0750502C13.262 0.025507 13.1313 0 12.9993 0C12.8673 0 12.7366 0.025507 12.6148 0.0750502C12.4929 0.124593 12.3824 0.197192 12.2894 0.288658L7.70992 4.7581C7.61697 4.84956 7.50638 4.92216 7.38453 4.9717C7.26269 5.02125 7.132 5.04676 7 5.04676C6.868 5.04676 6.73731 5.02125 6.61547 4.9717C6.49362 4.92216 6.38303 4.84956 6.29008 4.7581L1.7106 0.288658C1.61765 0.197192 1.50706 0.124593 1.38521 0.0750502C1.26337 0.025507 1.13268 0 1.00068 0C0.868682 0 0.737991 0.025507 0.616146 0.0750502C0.4943 0.124593 0.383712 0.197192 0.29076 0.288658C0.10453 0.471497 0 0.718831 0 0.976639C0 1.23445 0.10453 1.48178 0.29076 1.66462L4.88024 6.14382C5.44268 6.69206 6.20509 7 7 7C7.79491 7 8.55732 6.69206 9.11976 6.14382L13.7092 1.66462C13.8955 1.48178 14 1.23445 14 0.976639C14 0.718831 13.8955 0.471497 13.7092 0.288658Z" fill="#374557"></path></svg></span></div></a>
                                <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                    <a class="list-group-item" data-bs-toggle="tab" data-bs-target="#side__two" role="tab"><div class="nftmax__amount-dropdown"><img src="/assets/img/eth-icon.png" alt="#">ETH rate</div></a>
                                    <a class="list-group-item" data-bs-toggle="tab"  data-bs-target="#side__two_BTC" role="tab"><div class="nftmax__amount-dropdown"><img src="/assets/img/btc-icon.png" alt="#">BTC rate</div></a>
                                </ul>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="nav-tabContent">
                            <!-- Single Tab -->
                            <div class="tab-pane fade show active" id="side__two" role="tabpanel" aria-labelledby="side__two">
                                <div class="nftmax-amount__statics">
                                    <h4 class="nftmax-amount__statics__title">${{$transectionRateData['usd']}} USD</h4>
                                    <p class="nftmax-amount__statics__text">+{{$transectionRateData['growth']}} ({{$transectionRateData['growth_percentage']}}%)</p>
                                </div>
                                
                                <div class="nftmax-sidebar__cside-one">
                                    <canvas id="myChart_Side_Two"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="side__two_BTC" role="tabpanel" aria-labelledby="side__two">
                                <div class="nftmax-amount__statics">
                                    <h4 class="nftmax-amount__statics__title">${{$transectionRateBTCData['usd']}} USD</h4>
                                    <p class="nftmax-amount__statics__text">+{{$transectionRateBTCData['growth']}} ({{$transectionRateBTCData['growth_percentage']}}%)</p>
                                </div>
                                
                                <div class="nftmax-sidebar__cside-one">
                                    <canvas id="myChart_Side_Two_BTC"></canvas>
                                </div>
                            </div>
                            <!-- End Single Tab -->
                        </div>
                    </div>
                    <!-- End NFTMax Single Sidebar -->
                </div>
                    
                <div class="col-xxl-12 col-xl-6 col-12 nftmax-sidebar__widget">		
                    <!-- NFTMax Single Sidebar -->
                    <div class="nftmax-sidebar__single">
                        <!-- Sidebar Heading -->
                        <div class="nftmax-sidebar__heading">
                            <h4 class="nftmax-sidebar__title">Top Creators</h4>
                            <ul  class="nav nav-tabs nftmax-dropdown__list" id="nav-tab" role="tablist">
                                <li class="nav-item dropdown">
                                    <a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">View All <svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></a>
                                    <ul class="dropdown-menu nftmax-sidebar_dropdown">
                                        <a class="list-group-item" data-bs-toggle="tab" data-bs-target="#daily" role="tab">Daily</a>
                                        <a class="list-group-item" data-bs-toggle="tab" data-bs-target="#weekly" role="tab">Weekly</a>
                                        <a class="list-group-item"  data-bs-toggle="tab" data-bs-target="#monthly" role="tab">Monthly</a>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- Sidebar Creator Lists -->
                        <div class="nftmax-sidebar__creators">
                            <div class="tab-content" id="nav-tabContent">
                                <!-- Single Tab -->
                                <div class="tab-pane fade show active" id="daily" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <ul class="nftmax-sidebar__creatorlist">
                                        @foreach($TopCreators as $TopCreators)
                                        <li>
                                            <div class="nftmax-sidebar__creator">
                                                <img src="{{asset($TopCreators['0'])}}" alt="#">
                                                <a href="#"><b class="nftmax-sidebar__creator-name">{{$TopCreators['1']}}<span class="nftmax-sidebar__creator-link">{{$TopCreators['2']}}</span></b></a>
                                            </div>
                                            <div class="nftmax-sidebar__button">
                                                <a href="#" class="nftmax-sidebar__button-btn nftmax-request_request"><svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.1351 5.4852H11.1378V4.4879C11.1378 4.2125 10.9145 3.98926 10.6391 3.98926C10.3637 3.98926 10.1405 4.2125 10.1405 4.4879V5.4852H9.14317C8.86778 5.4852 8.64453 5.70845 8.64453 5.98384C8.64453 6.25924 8.86778 6.48248 9.14317 6.48248H10.1405V7.47979C10.1405 7.75518 10.3637 7.97843 10.6391 7.97843C10.9145 7.97843 11.1378 7.75518 11.1378 7.47979V6.48248H12.1351C12.4105 6.48248 12.6337 6.25924 12.6337 5.98384C12.6337 5.70845 12.4105 5.4852 12.1351 5.4852Z" fill="white"></path><path d="M5.15595 5.98378C6.80833 5.98378 8.14784 4.64426 8.14784 2.99189C8.14784 1.33951 6.80833 0 5.15595 0C3.50358 0 2.16406 1.33951 2.16406 2.99189C2.16406 4.64426 3.50358 5.98378 5.15595 5.98378Z" fill="white"></path><path d="M5.1558 6.98096C2.67838 6.98372 0.670727 8.99137 0.667969 11.4688C0.667969 11.7442 0.891215 11.9674 1.16661 11.9674H9.14497C9.42037 11.9674 9.64361 11.7442 9.64361 11.4688C9.64088 8.99137 7.63323 6.98369 5.1558 6.98096Z" fill="white"></path></svg></a>
                                                <a href="#" class="nftmax-sidebar__button-btn nftmax-request_close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- Single Tab -->
                                <div class="tab-pane fade show" id="weekly" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <ul class="nftmax-sidebar__creatorlist">
                                    @foreach($TopCreatorsWeekly as $TopCreatorsWeekly)
                                        <li>
                                            <div class="nftmax-sidebar__creator">
                                                <img src="{{asset($TopCreatorsWeekly['0'])}}" alt="#">
                                                <a href="#"><b class="nftmax-sidebar__creator-name">{{$TopCreatorsWeekly['1']}}<span class="nftmax-sidebar__creator-link">{{$TopCreatorsWeekly['2']}}</span></b></a>
                                            </div>
                                            <div class="nftmax-sidebar__button">
                                                <a href="#" class="nftmax-sidebar__button-btn nftmax-request_request"><svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.1351 5.4852H11.1378V4.4879C11.1378 4.2125 10.9145 3.98926 10.6391 3.98926C10.3637 3.98926 10.1405 4.2125 10.1405 4.4879V5.4852H9.14317C8.86778 5.4852 8.64453 5.70845 8.64453 5.98384C8.64453 6.25924 8.86778 6.48248 9.14317 6.48248H10.1405V7.47979C10.1405 7.75518 10.3637 7.97843 10.6391 7.97843C10.9145 7.97843 11.1378 7.75518 11.1378 7.47979V6.48248H12.1351C12.4105 6.48248 12.6337 6.25924 12.6337 5.98384C12.6337 5.70845 12.4105 5.4852 12.1351 5.4852Z" fill="white"></path><path d="M5.15595 5.98378C6.80833 5.98378 8.14784 4.64426 8.14784 2.99189C8.14784 1.33951 6.80833 0 5.15595 0C3.50358 0 2.16406 1.33951 2.16406 2.99189C2.16406 4.64426 3.50358 5.98378 5.15595 5.98378Z" fill="white"></path><path d="M5.1558 6.98096C2.67838 6.98372 0.670727 8.99137 0.667969 11.4688C0.667969 11.7442 0.891215 11.9674 1.16661 11.9674H9.14497C9.42037 11.9674 9.64361 11.7442 9.64361 11.4688C9.64088 8.99137 7.63323 6.98369 5.1558 6.98096Z" fill="white"></path></svg></a>
                                                <a href="#" class="nftmax-sidebar__button-btn nftmax-request_close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="tab-pane fade show" id="monthly" role="tabpanel" aria-labelledby="nav-profile-tab">
                                    <ul class="nftmax-sidebar__creatorlist">
                                    @foreach($TopCreatorsMonthly as $TopCreatorsMonthly)
                                        <li>
                                            <div class="nftmax-sidebar__creator">
                                                <img src="{{asset($TopCreatorsMonthly['0'])}}" alt="#">
                                                <a href="#"><b class="nftmax-sidebar__creator-name">{{$TopCreatorsMonthly['1']}}<span class="nftmax-sidebar__creator-link">{{$TopCreatorsMonthly['2']}}</span></b></a>
                                            </div>
                                            <div class="nftmax-sidebar__button">
                                                <a href="#" class="nftmax-sidebar__button-btn nftmax-request_request"><svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.1351 5.4852H11.1378V4.4879C11.1378 4.2125 10.9145 3.98926 10.6391 3.98926C10.3637 3.98926 10.1405 4.2125 10.1405 4.4879V5.4852H9.14317C8.86778 5.4852 8.64453 5.70845 8.64453 5.98384C8.64453 6.25924 8.86778 6.48248 9.14317 6.48248H10.1405V7.47979C10.1405 7.75518 10.3637 7.97843 10.6391 7.97843C10.9145 7.97843 11.1378 7.75518 11.1378 7.47979V6.48248H12.1351C12.4105 6.48248 12.6337 6.25924 12.6337 5.98384C12.6337 5.70845 12.4105 5.4852 12.1351 5.4852Z" fill="white"></path><path d="M5.15595 5.98378C6.80833 5.98378 8.14784 4.64426 8.14784 2.99189C8.14784 1.33951 6.80833 0 5.15595 0C3.50358 0 2.16406 1.33951 2.16406 2.99189C2.16406 4.64426 3.50358 5.98378 5.15595 5.98378Z" fill="white"></path><path d="M5.1558 6.98096C2.67838 6.98372 0.670727 8.99137 0.667969 11.4688C0.667969 11.7442 0.891215 11.9674 1.16661 11.9674H9.14497C9.42037 11.9674 9.64361 11.7442 9.64361 11.4688C9.64088 8.99137 7.63323 6.98369 5.1558 6.98096Z" fill="white"></path></svg></a>
                                                <a href="#" class="nftmax-sidebar__button-btn nftmax-request_close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Sidebar Creator Lists -->
                    </div>
                    <!-- End NFTMax Single Sidebar -->
                </div>
                
                <div class="col-xxl-12 col-xl-6 col-12 nftmax-sidebar__widget">	
                    <!-- NFTMax Single Sidebar -->
                    <div class="nftmax-sidebar__single">
                        <!-- Sidebar Heading -->
                        <div class="nftmax-sidebar__heading">
                            <h4 class="nftmax-sidebar__title">Top Flatform</h4>
                            <a href="#" class="nftmax-sidebar_btn">View All</a>
                        </div>
                        <!-- Platform List -->
                        <div class="nftmax-sidebar__platform">
                            <ul class="nftmax-sidebar__list">
                                @foreach($TopPlatform as $TopPlatform)
                                <li>
                                    <a href="#"><img src="{{ $TopPlatform['0'] }}" alt="#">{{ $TopPlatform['1'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- End NFTMax Single Sidebar -->
                </div>
            </div>
        </div>
    </div>
@stop

@section('code')
    <script type="text/javascript">
  		jQuery(document).ready(function($) {
			  $('[data-countdown]').each(function() {
				  var $this = $(this), finalDate = $(this).data('countdown');
				  $this.countdown(finalDate, function(event) {
					$this.html(event.strftime(' %H : %M : %S'));
				  });
			 });
		});

        const ctx_side_two = document.getElementById('myChart_Side_One').getContext('2d');
			var TotalSold    = @json($Statistics['TotalSold']);
			var TotalCancen  = @json($Statistics['TotalCancel']);
			var TotalPending = @json($Statistics['TotalPanding']);
			const dataSet = [TotalSold, TotalCancen, TotalPending]
			const myChart_Side_One = new Chart(ctx_side_two, {
				type: 'doughnut',
				
				data: {
					labels: [
						'Total Sold',
						'Total Cancel',
						'Total Planding'
					  ],
					  datasets: [{
						label: 'My First Dataset',
						data: dataSet,
						backgroundColor: [
						  '#5356FB',
						  '#F539F8',
						  '#FFC210',
						  '#E3E4FE'
						],
						hoverOffset: 2,
						borderWidth: 0,
					  }]
				},
				
				 options: {
				 
					responsive: true,
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Sell History'
					  }
					}
				}
				
			});


			const ctx_side_two_weekly = document.getElementById('myChart_Side_One_weekly').getContext('2d');
			var TotalSold    = @json($Statistics_weekly['TotalSold']);
			var TotalCancen  = @json($Statistics_weekly['TotalCancel']);
			var TotalPending = @json($Statistics_weekly['TotalPanding']);
			const dataSet_weekly = [TotalSold, TotalCancen, TotalPending]
			const myChart_Side_One_weekly = new Chart(ctx_side_two_weekly, {
				type: 'doughnut',
				
				data: {
					labels: [
						'Total Sold',
						'Total Cancel',
						'Total Planding'
					  ],
					  datasets: [{
						label: 'My First Dataset',
						data: dataSet_weekly,
						backgroundColor: [
						  '#5356FB',
						  '#F539F8',
						  '#FFC210',
						  '#E3E4FE'
						],
						hoverOffset: 2,
						borderWidth: 0,
					  }]
				},
				
				 options: {
				 
					responsive: true,
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Sell History'
					  }
					}
				}
				
			});

			const ctx_side_two_monthly = document.getElementById('myChart_Side_One_monthly').getContext('2d');
			var TotalSold    = @json($Statistics_monthly['TotalSold']);
			var TotalCancen  = @json($Statistics_monthly['TotalCancel']);
			var TotalPending = @json($Statistics_monthly['TotalPanding']);
			const dataSet_monthly = [TotalSold, TotalCancen, TotalPending]
			const myChart_Side_One_monthly = new Chart(ctx_side_two_monthly, {
				type: 'doughnut',
				
				data: {
					labels: [
						'Total Sold',
						'Total Cancel',
						'Total Planding'
					  ],
					  datasets: [{
						label: 'My First Dataset',
						data: dataSet_monthly,
						backgroundColor: [
						  '#5356FB',
						  '#F539F8',
						  '#FFC210',
						  '#E3E4FE'
						],
						hoverOffset: 2,
						borderWidth: 0,
					  }]
				},
				
				 options: {
				 
					responsive: true,
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Sell History'
					  }
					}
				}
				
			});
			
			const ctx_side_three = document.getElementById('myChart_Side_Two').getContext('2d');
			var label = @json($transectionRate[0]);
			var data  = @json($transectionRate[1]);
			const myChart_Side_Two = new Chart(ctx_side_three, {
				type: 'line',
				
				data: {
					labels: label,
					datasets: [{
						label: 'Visitor',
						data: data,
						backgroundColor: '#D8D8FE',
						borderColor:'#5356FB',
						pointRadius: 3,
						pointBackgroundColor: '#5356FB',
						pointBorderColor: '#5356FB',
						borderWidth:4,
						tension: 0.9,
						fill:true,
						fillColor:'#fff',
						
					}]
				},
				
				 options: {
					responsive: true,
					scales: {
						x:{
							grid:{
								display:false,
								drawBorder: false,
							},
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							},
							ticks:{
								display:false
							}
						},
					},
					
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Visitor: 2k'
					  }
					}
				}
			});
			const ctx_side_three_BTC = document.getElementById('myChart_Side_Two_BTC').getContext('2d');
			var label = @json($transectionRateBTC[0]);
			var data  = @json($transectionRateBTC[1]);
			const myChart_Side_Two_BTC = new Chart(ctx_side_three_BTC, {
				type: 'line',
				
				data: {
					labels: label,
					datasets: [{
						label: 'Visitor',
						data: data,
						backgroundColor: '#D8D8FE',
						borderColor:'#5356FB',
						pointRadius: 3,
						pointBackgroundColor: '#5356FB',
						pointBorderColor: '#5356FB',
						borderWidth:4,
						tension: 0.9,
						fill:true,
						fillColor:'#fff',
						
					}]
				},
				
				 options: {
					responsive: true,
					scales: {
						x:{
							grid:{
								display:false,
								drawBorder: false,
							},
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							},
							ticks:{
								display:false
							}
						},
					},
					
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Visitor: 2k'
					  }
					}
				}
			});

            const ctx = document.getElementById('myChart_one').getContext('2d');
            var day       = @json($SellHistory[0]);
            var avgSell   = @json($SellHistory[1]);
            var totalSell = @json($SellHistory[2]);
			const myChart_one = new Chart(ctx, {
				type: 'bar',
				
				data: {
					labels: day,
					datasets: [{
						label: 'AVG Sale',
						data: avgSell,
						backgroundColor: [
							'#5356FB',
							'#5356FB',
							'#5356FB',
							'#5356FB',
							'#5356FB',
							'#5356FB',
							'#5356FB',
						],
						
						fill: true,
						tension:0.4,
						borderWidth: 0,
						borderSkipped:false,
						borderRadius:3,
						barPercentage:0.4,
						categoryPercentage:0.4,
					},{
						label: 'Total Sell',
						data: totalSell,
						backgroundColor: [
							'#F239F5',
							'#F239F5',
							'#F239F5',
							'#F239F5',
							'#F239F5',
							'#F239F5',
							'#F239F5',
						],
						borderWidth: 0,
						borderSkipped:false,
						borderRadius:3,
						categoryPercentage:0.4,
						barPercentage: 0.4
					}]
				},
				
				 options: {
					maintainAspectRatio: false,
					responsive: true,
					scales: {
						x:{
							grid:{
								display:false,
								drawBorder: false,
							},
							
						},
						y:{
							grid:{
								drawBorder: false,
							},
						},
					},
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Sell History'
					  }
					}
				}
			});
			
			const ctx_two = document.getElementById('myChart_two').getContext('2d');
            var day = @json($MarketVisitor[0]);
            var visitor = @json($MarketVisitor[1]);
			const myChart_two = new Chart(ctx_two, {
				type: 'line',
				
				data: {
					labels: day,
					datasets: [{
						label: 'Visitor',
						data:visitor,
						backgroundColor: '#FAECFF',
						borderColor:'#DE3DF8',
						pointRadius: 5,
						pointBackgroundColor: '#fff',
						pointBorderColor: '#AE8FF7',
						tension: 0.6,
						borderWidth:4,
						fill:true,
						fillColor:'#fff',
					}]
				},
				
				 options: {
					maintainAspectRatio: false,
					responsive: true,
					scales: {
						x:{
							grid:{
								display:false,
								drawBorder: false,
							},
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							},
							ticks:{
								display:false
							}
						},
					},
					
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Visitor: 2k'
					  }
					}
				}
			});


			const ctx_two_monthly = document.getElementById('myChart_two_monthly').getContext('2d');
            var day = @json($MarketVisitorMonthly[0]);
            var visitor = @json($MarketVisitorMonthly[1]);
			const myChart_two_monthly = new Chart(ctx_two_monthly, {
				type: 'line',
				
				data: {
					labels: day,
					datasets: [{
						label: 'Visitor',
						data:visitor,
						backgroundColor: '#FAECFF',
						borderColor:'#DE3DF8',
						pointRadius: 5,
						pointBackgroundColor: '#fff',
						pointBorderColor: '#AE8FF7',
						tension: 0.6,
						borderWidth:4,
						fill:true,
						fillColor:'#fff',
					}]
				},
				
				 options: {
					maintainAspectRatio: false,
					responsive: true,
					scales: {
						x:{
							grid:{
								display:false,
								drawBorder: false,
							},
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							},
							ticks:{
								display:false
							}
						},
					},
					
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Visitor: 2k'
					  }
					}
				}
			});
			const ctx_two_weekly = document.getElementById('myChart_two_weekly').getContext('2d');
            var day = @json($MarketVisitorWeekly[0]);
            var visitor = @json($MarketVisitorWeekly[1]);
			const myChart_two_weekly = new Chart(ctx_two_weekly, {
				type: 'line',
				
				data: {
					labels: day,
					datasets: [{
						label: 'Visitor',
						data:visitor,
						backgroundColor: '#FAECFF',
						borderColor:'#DE3DF8',
						pointRadius: 5,
						pointBackgroundColor: '#fff',
						pointBorderColor: '#AE8FF7',
						tension: 0.6,
						borderWidth:4,
						fill:true,
						fillColor:'#fff',
					}]
				},
				
				 options: {
					maintainAspectRatio: false,
					responsive: true,
					scales: {
						x:{
							grid:{
								display:false,
								drawBorder: false,
							},
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							},
							ticks:{
								display:false
							}
						},
					},
					
					plugins: {
					  legend: {
						position: 'top',
						display: false,
					  },
					  title: {
						display: false,
						text: 'Visitor: 2k'
					  }
					}
				}
			});

  	</script>

<script type="text/javascript">
  		Vue.ready(function () {
  			var self = new Vue({
			  	vuetify: new Vuetify(),
				el: '#body',
			  	data: {
			  		wait: false,
			  		menu: false,
			  		snap: null,
			  		pick: null,
			  		sync: null,
					view: 1,
			  		take: 32,
			  		high: 0,
			  		page: 0,
			  		task: {!!json_encode($task)!!},
					pile: {
						role: {
							wait: false,
							text: null,
							list: []
						}
					},
			  		data: {
			  			page: 0,
			  			itemsPerPage: 32
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
			  			lock: null,
			  			test: null,
			  			type: null,
			  			code: null,
			  			nick: null,
			  			name: null,
			  			last: null,
			  			cell: null,
			  			mail: null,
			  			pass: null,
			  			note: null,
			  			show: null
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
			    		axios.get("{{route('users', ['task' => 'load'])}}?take=" + (take || '') + '&page=' + (page || '') +
			    			                                                                           '&find=' + (text || '') +
			    			                                                                           '&type=' + (type || ''), {})
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
			    				    axios.get(`{{route('users', ['task' => 'load'])}}/${item.hash}`, {})
							             .then(function (data) {
							            self.form = {lock: data.data.lock,
							            	         test: data.data.test,
													 role: data.data.role,
					             			         code: data.data.code,
					             			         nick: data.data.nick,
					             			         last: data.data.last,
			             			    	         name: data.data.name,
			             			    	         cell: data.data.cell,
			             			    	         mail: data.data.mail,
			             			    	         note: data.data.note,
			             			    	         show: false,
			             			    	         pass: null};

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
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: fail.response.data.text};
			                            } else {
			                            	self.note = {show: true,
			                            	             type: 'fail',
			                            	             text: 'Se presentó un error inesperado.'};
			                            }
							        });

									self.time = setTimeout(function () {
										self.wait = true;
									}, 100);

							        self.pick = item;
			    					break;
			    				case 'crop':
			    				    var file = data.files.item(0);

			    					if (file.type.match('image.*')) {
										var reader = new FileReader();

										reader.onload = function(event) {
											self.file = event.target.result;

											self.show.crop = true;

											self.pick = item;

											if (self.snap) {
												self.snap.replace(self.file, false);
											}
										};

										reader.readAsDataURL(file);
									}
			    					break
			    				case 'wipe':
			    					self.show.wipe = true;

			    			    	self.pick = item;
			    			    	break;
			    			    case 'wait':
			    					self.show.wait = true;

			    			    	self.pick = item;
			    			    	break;
			    			    case 'lock':
			    			        self.show.lock = true;

			    			    	self.pick = item;
			    			    	break;
			    				case 'drop':
			    				    self.show.drop = true;

			    				    self.pick = item;
			    					break;
			    			}
			    		} else {
			    			switch (task) {
			    				case 'make':
			    					self.form = {lock: 0,
			    						         test: 0,
			    						         role: 0,
			    						         code: null,
									  			 nick: null,
									  			 pass: null,
									  			 last: null,
									  			 name: null,
									  			 cell: null,
									  			 mail: null,
									  			 note: null,
									  			 show: false};

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

		    		    data.append('lock', (form.lock ? 1 : 0));

		    		    data.append('test', (form.test ? 1 : 0));

		    		    data.append('type', (form.type || 0));

		    		    data.append('code', (form.code || ''));

		    		    data.append('nick', (form.nick || ''));

		    		    data.append('pass', (form.pass || ''));

		    		    data.append('last', (form.last || ''));

		    		    data.append('name', (form.name || ''));

		    	        data.append('cell', (form.cell || ''));

		    	        data.append('mail', (form.mail || ''));

		    	        data.append('note', (form.note || ''));

		    	        if (item) {
				    		axios.post("{{route('users', ['task' => 'save'])}}/" + item.hash, data, {'X-CSRF-TOKEN': '{{csrf_token()}}'})
	                             .then(function (data) {
								self.view = 1;

	                            self.wait = false;
								
	                            self.note = {show: true,
                                	         type: 'done',
                                	         text: data.data.text};

                                item.type = form.type;

                                item.code = form.code;

                                item.nick = form.nick;

                                item.last = form.last;

                                item.name = form.name;

				    	        item.cell = form.cell;

				    	        item.mail = form.mail;

				    	        item.note = form.note;
	                        })
	                        .catch(function (fail) {
	                            self.wait = false;

	                            if (fail.response.data.text) {
	                            	self.fail = {text: fail.response.data.text,
	                            		         list: fail.response.data.list || {}};
	                            } else {
	                            	self.fail.text = 'Se presentó un error inesperado.';
	                            }
	                        });
				    	} else {
				    		axios.post("{{route('users', ['task' => 'make'])}}", data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
	                             .then(function (data) {
	                            self.list.unshift((item = {item: data.data.item,
                                	                       hash: data.data.hash,
                                	                       code: data.data.code,
                                	                       tone: data.data.tone,
                                	                       face: data.data.face,
                            		                       lock: form.lock,
                            		                       type: form.type,
                            		                       nick: form.nick,
                            		                       last: form.last,
                            		                       name: form.name,
                            		                       mail: form.mail,
                            		                       seen: null}));

                                self.note = {show: true,
                                	         type: 'done',
                                	         text: data.data.text};

                                self.wait = false;

								self.view = 1;
	                        })
	                        .catch(function (fail) {
	                            self.wait = false;

	                            if (fail.response.data.text) {
	                            	self.fail = {text: fail.response.data.text,
	                            		         list: fail.response.data.list || {}};
	                            } else {
	                            	self.fail.text = 'Se presentó un error inesperado.';
	                            }
	                        });
				    	}

				    	self.wait = true;
			    	},
			    	face: function (item, snap) {
			    		var data = new FormData();

						if (snap) {
							data.append('file', self.blob(snap.getCroppedCanvas({width: 640, height: 640})));
						}

			    		axios.post("{{route('users', ['task' => 'face'])}}/" + item.hash, data, {headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'}})
                             .then(function (data) {
                            item.face = data.data.file;

                            self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
                            
                            self.show.crop = false;

                            self.show.wipe = false;

                            self.wait = false;
                        })
                        .catch(function (fail) {
                            self.wait = false;

                            if (fail.response.data.text) {
                            	self.fail = {text: fail.response.data.text,
                            		         list: fail.response.data.list || {}};
                            } else {
                            	self.fail.text = 'Se presentó un error inesperado.';
                            }
                        });

                        self.wait = true;
			    	},
			    	lock: function (item, wait) {
			    		axios.get("{{route('users')}}/" + (wait ? 'wait' : 'lock') + '/' + item.hash, {})
				             .then(function (data) {
				            self.wait = false;

				            self.show.lock = false;

				            self.show.wait = false;

				            item.wait = wait ? null :
				                               item.wait;

			             	item.lock = wait ? item.lock :
			             	                   data.data.lock;

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				            self.wait = false;

				            self.show.lock = false;

				            self.show.wait = false;

				            if (fail.response.data.text) {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: fail.response.data.text};
                            } else {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
                            }
				        });

				        self.wait = true;
			    	},
			    	drop: function (item) {
			    		axios.get("{{route('users', ['task' => 'drop'])}}/" + item.hash, {})
				             .then(function (data) {
				            self.wait = false;

				            self.show.drop = false;

			             	self.list.splice(self.list.indexOf(item), 1);

		             		self.note = {show: true,
                            	         type: 'done',
                            	         text: data.data.text};
				        }).catch(function (fail) {
				            self.wait = false;

				            self.show.drop = false;

				            if (fail.response.data.text) {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: fail.response.data.text};
                            } else {
                            	self.note = {show: true,
                            	             type: 'fail',
                            	             text: 'Se presentó un error inesperado.'};
                            }
				        });

				        this.wait = true;
			    	},
			    	boot: function(data) {
						self.snap = data;

						self.snap.replace(self.file, false);
					}
			    },
			    mounted: function () {
			    	this.load(this.take, this.page, this.sort.text, this.sort.type, function (fail) {
	             		setTimeout(function () {
	             			self.done = true;
	             		}, 500);
	             	});
			    }
			})
  		});
  	</script>
@stop