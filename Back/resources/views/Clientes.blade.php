@include('Layout.Header')
			<!-- NFTmax Dashboard -->
			<section class="nftmax-adashboard nftmax-show">
				<div class="container">
					<div class="row">	
						<div class="col-lg-12 col-12">
							<div class="nftmax-body">
								<!-- Dashboard Inner -->
								<div class="nftmax-dsinner">
									<!-- Dashboard Slider -->
									<div class="nftmax-wallet__dashboard">
										
										
										
										
										
										
										
										<div class="row">
											<div class="col-12">
												<div class="nftmax-table mg-top-40">
													<div class="nftmax-table__heading">
														<h3 class="nftmax-table__title mb-0">Clientes <span class="nftmax-table__badge">120</span></h3>
														<ul  class="nav nav-tabs  nftmax-dropdown__list" id="nav-tab" role="tablist">
															<li class="nav-item dropdown ">
																<a class="nftmax-sidebar_btn nftmax-heading__tabs nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Categorías <span class="nftmax-table__arrow--icon"><svg width="13" height="6" viewBox="0 0 13 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.7" d="M12.4124 0.247421C12.3327 0.169022 12.2379 0.106794 12.1335 0.0643287C12.0291 0.0218632 11.917 0 11.8039 0C11.6908 0 11.5787 0.0218632 11.4743 0.0643287C11.3699 0.106794 11.2751 0.169022 11.1954 0.247421L7.27012 4.07837C7.19045 4.15677 7.09566 4.219 6.99122 4.26146C6.88678 4.30393 6.77476 4.32579 6.66162 4.32579C6.54848 4.32579 6.43646 4.30393 6.33202 4.26146C6.22758 4.219 6.13279 4.15677 6.05312 4.07837L2.12785 0.247421C2.04818 0.169022 1.95338 0.106794 1.84895 0.0643287C1.74451 0.0218632 1.63249 0 1.51935 0C1.40621 0 1.29419 0.0218632 1.18975 0.0643287C1.08531 0.106794 0.990517 0.169022 0.910844 0.247421C0.751218 0.404141 0.661621 0.616141 0.661621 0.837119C0.661621 1.0581 0.751218 1.2701 0.910844 1.42682L4.84468 5.26613C5.32677 5.73605 5.98027 6 6.66162 6C7.34297 6 7.99647 5.73605 8.47856 5.26613L12.4124 1.42682C12.572 1.2701 12.6616 1.0581 12.6616 0.837119C12.6616 0.616141 12.572 0.404141 12.4124 0.247421Z" fill="#374557" fill-opacity="0.6"></path></svg></span></a>
																<ul class="dropdown-menu nftmax-sidebar_dropdown">
																	<a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_1" role="tab">Pago x Servicio</a>
																	<a class="dropdown-item list-group-item" data-bs-toggle="tab" data-bs-target="#table_2" role="tab">Fee Mensual</a>
																	<a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_3" role="tab">Fee Ilimitado</a>
																	<a class="dropdown-item list-group-item"  data-bs-toggle="tab" data-bs-target="#table_4" role="tab">Bolsa de Horas</a>
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
																		<th class="nftmax-table__column-1 nftmax-table__h1">Nombre</th>
																		<th class="nftmax-table__column-2 nftmax-table__h2">Categoría</th>
																		<th class="nftmax-table__column-3 nftmax-table__h3">Consumo</th>
																		<th class="nftmax-table__column-4 nftmax-table__h4">Bolsa</th>
																		<th class="nftmax-table__column-5 nftmax-table__h5">Estado</th>
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
																				<a href="#"><h4 class="nftmax-table__product-title">{{ $user['1'] }}</h4></a>
																				<p class="nftmax-table__product-desc">Usuario  <a href="#">{{ $user['2'] }}</a></p>
																			</div>
																		</div>
																	</td>
																	@if($user['3']=='Pago x Servicio')
																	<td class="nftmax-table__column-7 nftmax-table__data-7">
																		<div class="nftmax-table__status nftmax-gbcolor">{{ $user['3'] }}</div>
																	</td>
																	@endif
																	@if($user['3']=='Fee Mensual')
																	<td class="nftmax-table__column-7 nftmax-table__data-7">
																		<div class="nftmax-table__status nftmax-rbcolor">{{ $user['3'] }}</div>
																	</td>
																	@endif
																	@if($user['3']=='Fee Ilimitado')
																	<td class="nftmax-table__column-7 nftmax-table__data-7">
																		<div class="nftmax-table__status nftmax-bbcolor">{{ $user['3'] }}</div>
																	</td>
																	@endif
																	@if($user['3']=='Bolsa de Horas')
																	<td class="nftmax-table__column-7 nftmax-table__data-7">
																		<div class="nftmax-table__status nftmax-zbcolor">{{ $user['3'] }}</div>
																	</td>
																	@endif
																	<td class="nftmax-table__column-3 nftmax-table__data-3">
																		<div class="nftmax-table__amount nftmax-table__text-two">
																			<span class="nftmax-table__text">{{ $user['4'] }}</span>
																		</div>
																	</td>
																	<td class="nftmax-table__column-4 nftmax-table__data-4">
																		<p class="nftmax-table__text nftmax-table__up-down">{{ $user['5'] }}</p>
																	</td>
																	
																	@if($user['6']==1)
																		<td class="nftmax-table__column-7 nftmax-table__data-7">
																		<div class="nftmax-table__status nftmax-sbcolor">Inactivo</div>
																		</td>
																	@else
																	<td class="nftmax-table__column-7 nftmax-table__data-7">
																		<div class="nftmax-table__status nftmax-gbcolor">Activo</div>
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
										</div>
									</div>	
									<!-- End Dashboard Slider -->
									
								</div>
								<!-- End Dashboard Inner -->
							</div>
						</div>

										

<!-- Footer -->
@include('Layout.Footer')

		<script>
		
			const ctx_history_one = document.getElementById('myChart_history_one').getContext('2d');
			var date = @json($Total_sell[0]);
			var visitors = @json($Total_sell[1]);
			const myChart_history_one = new Chart(ctx_history_one, {
				type: 'line',
				data: {
					labels: date,
					datasets: [{
						label: 'Visitor',
						data: visitors,
						borderColor:'#5356FB',
						tension: 0.5,
						borderWidth:4,
						pointRadius: 5,
						pointBackgroundColor: '#5356FB',
						pointBorderColor: '#d5dff54f',
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
							ticks:{
								display:false
							}
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
			
			const ctx_history_two = document.getElementById('myChart_history_two').getContext('2d');
			var date      = @json($ActiveCustomer[0]);
			var ActiveNow = @json($ActiveCustomer[1]);
			const myChart_history_two = new Chart(ctx_history_two, {
				type: 'line',
				data: {
					labels: date,
					datasets: [{
						label: 'Visitor',
						data: ActiveNow,
						borderColor:'#F539F8',
						tension: 0.5,
						borderWidth:4,
						pointRadius: 5,
						pointBackgroundColor: '#F539F8',
						pointBorderColor: '#d5dff54f',
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
							ticks:{
								display:false
							}
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
			
			const ctx_history_three = document.getElementById('myChart_history_three').getContext('2d');
			var date          = @json($Total_Prodcuts[0]);
			var Total_product = @json($Total_Prodcuts[1]);
			const myChart_history_three = new Chart(ctx_history_three, {
				type: 'line',
				data: {
					labels: date,
					datasets: [{
						label: 'Visitor',
						data: Total_product,
						borderColor:'#27AE60',
						tension: 0.5,
						borderWidth:4,
						pointRadius: 5,
						pointBackgroundColor: '#27AE60',
						pointBorderColor: '#d5dff54f',
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
							ticks:{
								display:false
							}
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
			
			const ctx_history_four = document.getElementById('myChart_history_four').getContext('2d');
			var date          = @json($Close_Offer[0]);
			var Close_Offer = @json($Close_Offer[1]);
			const myChart_history_four = new Chart(ctx_history_four, {
				type: 'line',
				data: {
					labels: date,
					datasets: [{
						label: 'Visitor',
						data: Close_Offer,
						borderColor:'#EB5757',
						tension: 0.5,
						borderWidth:4,
						pointRadius: 5,
						pointBackgroundColor: '#EB5757',
						pointBorderColor: '#d5dff54f',
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
							ticks:{
								display:false
							}
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
			
			
			const ctx_market = document.getElementById('myChart_market_history').getContext('2d');
			var day      = @json($MarketHistory[0]);
			var visitors = @json($MarketHistory[1]);
			var selles   = @json($MarketHistory[2]);

			const myChart_market_history = new Chart(ctx_market, {
				type: 'line',
				
				data: {
					labels: day,
					datasets: [{
						label: 'Visitors',
						data:visitors,
						backgroundColor: 'transparent',
						borderColor:'#F539F8',
						borderWidth:4,
						fill:true,
						fillColor:'#fff',
						tension: 0.4,
					},{
						label: 'Sells',
						data: selles,
						backgroundColor: 'transparent',
						borderColor:'#5356FB',
						borderWidth:4,
						fill:true,
						tension: 0.4,
						fillColor:'#fff',
					}]
				},
				
				 options: {
					scales: {
						x:{
							grid:{
								drawBorder: false,
							}
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							}
						},
					},
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

			const ctx_market_7days = document.getElementById('myChart_market_history_7days').getContext('2d');
			var day      = @json($MarketHistory_7days[0]);
			var visitors = @json($MarketHistory_7days[1]);
			var selles   = @json($MarketHistory_7days[2]);

			const myChart_market_history_7days = new Chart(ctx_market_7days, {
				type: 'line',
				
				data: {
					labels: day,
					datasets: [{
						label: 'Visitors',
						data:visitors,
						backgroundColor: 'transparent',
						borderColor:'#F539F8',
						borderWidth:4,
						fill:true,
						fillColor:'#fff',
						tension: 0.4,
					},{
						label: 'Sells',
						data: selles,
						backgroundColor: 'transparent',
						borderColor:'#5356FB',
						borderWidth:4,
						fill:true,
						tension: 0.4,
						fillColor:'#fff',
					}]
				},
				
				 options: {
					scales: {
						x:{
							grid:{
								drawBorder: false,
							}
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							}
						},
					},
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

			const ctx_market_30days = document.getElementById('myChart_market_history_30days').getContext('2d');
			var day      = @json($MarketHistory_30days[0]);
			var visitors = @json($MarketHistory_30days[1]);
			var selles   = @json($MarketHistory_30days[2]);

			const myChart_market_history_30days = new Chart(ctx_market_30days, {
				type: 'line',
				
				data: {
					labels: day,
					datasets: [{
						label: 'Visitors',
						data:visitors,
						backgroundColor: 'transparent',
						borderColor:'#F539F8',
						borderWidth:4,
						fill:true,
						fillColor:'#fff',
						tension: 0.4,
					},{
						label: 'Sells',
						data: selles,
						backgroundColor: 'transparent',
						borderColor:'#5356FB',
						borderWidth:4,
						fill:true,
						tension: 0.4,
						fillColor:'#fff',
					}]
				},
				
				 options: {
					scales: {
						x:{
							grid:{
								drawBorder: false,
							}
							
						},
						y:{
							grid:{
								display:false,
								drawBorder: false,
							}
						},
					},
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
						data:avgSell,
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
			var month = @json($MarketVisitor[0]);
            var visitorData = @json($MarketVisitor[1]);
			const myChart_two = new Chart(ctx_two, {
				type: 'line',
				
				data: {
					labels: month,
					datasets: [{
						label: 'Visitor',
						data: visitorData,
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
			const ctx_two_7days = document.getElementById('myChart_two_7days').getContext('2d');
			var month = @json($MarketVisitor_7days[0]);
            var visitorData = @json($MarketVisitor_7days[1]);
			const myChart_two_7days = new Chart(ctx_two_7days, {
				type: 'line',
				
				data: {
					labels: month,
					datasets: [{
						label: 'Visitor',
						data: visitorData,
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
			
			const ctx_two_30days = document.getElementById('myChart_two_30days').getContext('2d');
			var month = @json($MarketVisitor_30days[0]);
            var visitorData = @json($MarketVisitor_30days[1]);
			const myChart_two_30days = new Chart(ctx_two_30days, {
				type: 'line',
				
				data: {
					labels: month,
					datasets: [{
						label: 'Visitor',
						data: visitorData,
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