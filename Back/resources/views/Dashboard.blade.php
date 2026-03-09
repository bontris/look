@include('Layout.Header')
				
			<!-- NFTmax Dashboard -->
			<section class="nftmax-adashboard nftmax-show">
				<div class="container">
					<div class="row">	
						<div class="col-xxl-9 col-12 nftmax-main__column">
							<div class="nftmax-body">
								<!-- Dashboard Inner -->
								<div class="nftmax-dsinner">
									<!-- Dashboard Slider -->
									<div class="dashboard-banner nftmax-bg-cover mg-top-40" style="background-image:url('assets/img/banner-bg.png')">
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
											<h3 class="nftmax-table__title mb-0">Servicios <span class="nftmax-table__badge">435</span></h3>
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
                                                    @foreach($AllNFTSUpdate as $user)
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
																	<img src="assets/img/eth-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['3'] }}</span>
																</div>
															</td>
															<td class="nftmax-table__column-3 nftmax-table__data-3">
																<div class="nftmax-table__amount nftmax-table__text-two">
																	<img src="assets/img/usd-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['4'] }}$</span>
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
																	<img src="assets/img/eth-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['3'] }}</span>
																</div>
															</td>
															<td class="nftmax-table__column-3 nftmax-table__data-3">
																<div class="nftmax-table__amount nftmax-table__text-two">
																	<img src="assets/img/usd-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['4'] }}$</span>
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
																	<img src="assets/img/eth-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['3'] }}</span>
																</div>
															</td>
															<td class="nftmax-table__column-3 nftmax-table__data-3">
																<div class="nftmax-table__amount nftmax-table__text-two">
																	<img src="assets/img/usd-icon.png" alt="#"><span class="nftmax-table__text">{{ $user['4'] }}$</span>
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
@include('Layout.RightSidebar')						
@include('Layout.Footer')		
		
		<script>
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
		
	</body>
</html>