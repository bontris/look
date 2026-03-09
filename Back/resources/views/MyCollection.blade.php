@include('Layout.Header')

			<!-- NFTmax Dashboard -->
			<section class="nftmax-adashboard nftmax-show">
			
				<div class="container">
					<div class="row">	
						<div class="col-lg-9 col-12 nftmax-main__column">
							<div class="nftmax-body">
								<!-- Dashboard Inner -->
								<div class="nftmax-dsinner">
									<div class="nftmax-inner__heading mg-btm-20">
										<h2 class="nftmax-inner__page-title m-0">My Collection</h2>
									</div>
									
									<div class="row nftmax-gap-sq30 ">
										@foreach($collection as $collection)
										<div class="col-lg-4 col-md-6 col-12">
											<div class="nftmax-collection__single">
												<div class="nftmax-collection__head">
													<a href="#"><img class="nftmax-collection__img" src="{{ asset($collection['0']) }}" alt="#"></a>
												</div>
												<div class="nftmax-collection__body">
													<div class="row g-2">
														<div class="col-6">
															<a href="#"><img class="nftmax-collection__img" src="{{ asset($collection['1']) }}" alt="#"></a>
														</div>
														<div class="col-6">
															<a href="#"><img class="nftmax-collection__img" src="{{ asset($collection['2']) }}" alt="#"></a>
														</div>
													</div>
													<div class="nftmax-collection__author">
														<div class="nftmax-collection__author-head">
															<a href="#">
																<img src="{{ asset($collection['3']) }}" alt="#">
																<h4 class="nftmax-collection__title">{{ $collection['4'] }}</h4>
															</a>
														</div>
														<div class="nftmax-collection__item"><a href="#">{{ $collection['5'] }} Item</a></div>
													</div>
												</div>
											</div>
										</div>
										@endforeach
									</div>
								</div>
								<!-- End Dashboard Inner -->
							</div>
						</div>

@include('Layout.RightSidebar')
@include('Layout.Footer')

