@include('Layout.Header')
			<!-- NFTmax Dashboard -->
			<section class="nftmax-adashboard nftmax-show">
				<div class="container">
					<div class="row">	
						<div class="col-lg-9 col-12 nftmax-main__column">
							<div class="nftmax-body">
								<!-- Dashboard Inner -->
								<div class="nftmax-dsinner">
								<!-- All Notification Heading -->
								<div class="nftmax-inner__heading">
									<h2 class="nftmax-inner__page-title">Inbox</h2>
								</div>
								<!-- End All Notification Heading -->
								<div class="nftmax-chatbox">
									<div class="row">
										<div class="col-lg-5 col-md-5 col-12">
											<div class="nftmax-chatbox__sidebar">
												<div class="nftmax-chatbox__first-group">
													<!-- Title -->
													<h4 class="nftmax-chatbox__title">Message</h4>
													<!-- Chatbox Form -->
													<div class="nftmax-header__form nftmax-chatbox__search">
														<form class="nftmax-header__form-inner" action="#">
															<button class="search-btn" type="submit"><i class="fa-solid fa-magnifying-glass-minus"></i></button>
															<input name="s" value="" type="text" placeholder="Search items, collections...">
														</form>
													</div>
												</div>
												
												<!-- Chatbox List -->
												<ul class="nftmax-chatbox__list">
													<!-- Single List -->
                                                    @foreach($AllMessage as $AllMessage)
													<li>
														<div class="nftmax-chatbox__author">
															<div class="nftmax-chatbox__author-img">
																<img src="{{ asset($AllMessage['0']) }}" alt="#">
																<span class="nftmax-chatbox__author-online"></span>
															</div>
															<div class="nftmax-chatbox__author-content">
																<h4 class="nftmax-chatbox__author-title">{{ $AllMessage['1'] }}</h4>
																<p class="nftmax-chatbox__author-desc">{{ $AllMessage['2'] }}</p>
															</div>
														</div>
														<div class="nftmax-chatbox__right">
															<p class="nftmax-chatbox__time">{{ $AllMessage['3'] }}</p>
															<span class="nftmax-chatbox__unread">{{ $AllMessage['4'] }}</span>
														</div>
													</li>
                                                    @endforeach
													<!-- End Single List -->
												</ul>
											</div>
										</div>
										
										<div class="col-lg-7 col-md-7 col-12">
											<div class="nftmax-chatbox__explore">
												<div class="nftmax-chatbox__explore-head">
													<div class="nftmax-chatbox__author">
														<div class="nftmax-chatbox__author-img">
															<img src="assets/img/chat-top-ms.png" alt="#">
														</div>
														<div class="nftmax-chatbox__author-content">
															<h4 class="nftmax-chatbox__author-title">Bessie Cooper</h4>
															<p class="nftmax-chatbox__author-desc">Nft Seller</p>
														</div>
													</div>
													<div class="nftmax-chatbox__right-bar"><i class="fa-solid fa-ellipsis-vertical"></i></div>
												</div>
												
												<div class="nftmax-chatbox__explore-body">
													<!-- Incomming List -->
													<div class="nftmax-chatbox__incoming">
														<ul class="nftmax-chatbox__incoming-list">
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__main-content nftmax-chatbox__only-text">
																		<div class="nftmax-chatbox__incoming-chat">
																			<p class="nftmax-chatbox__incoming-text">next time you'll be awake at this hour why not now</p>
																		</div>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__main-content nftmax-chatbox__only-text">
																		<div class="nftmax-chatbox__incoming-chat">
																			<p class="nftmax-chatbox__incoming-text">Didn't I tell you not to put your phone on charge just because it's the weekend?</p>
																		</div>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__author-img">
																		<img src="assets/img/chat-author11.png" alt="#">
																	</div>
																	<div class="nftmax-chatbox__main-content">
																		<div class="nftmax-chatbox__incoming-chat">
																			<ul class="nftmax-chatbox__imoji">
																				<li><img src="assets/img/imoji.png" alt="#"></li>
																				<li><img src="assets/img/imoji.png" alt="#"></li>
																				<li><img src="assets/img/imoji.png" alt="#"></li>
																			</ul>
																		</div>
																		<p class="nftmax-chatbox__time  nftmax-chatbox__time-two">Sat 5:10 AM</p>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
														</ul>
													</div>
													<!-- End Incomming List -->
													
													<!-- Outgoing List -->
													<div class="nftmax-chatbox__incoming nftmax-chatbox__outgoing">
														<ul class="nftmax-chatbox__incoming-list">
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__main-content">
																		<div class="nftmax-chatbox__incoming-chat">
																			<p class="nftmax-chatbox__incoming-text">i woke up calmnnn i put it on the charger the phone was turned off next to me i took it out while i was sleeping hsadfkagshdfgsajf i slept early girl i slept at 3</p>
																		</div>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__main-content">
																		<div class="nftmax-chatbox__incoming-chat">
																			<p class="nftmax-chatbox__incoming-text">While you win in love, you continue to win in other things dhdhdhdh into the find to make</p>
																		</div>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__main-content">
																		<div class="nftmax-chatbox__incoming-chat">
																			<ul class="nftmax-chatbox__imoji">
																				<li><img src="assets/img/fire.png" alt="#"></li>
																				<li><img src="assets/img/fire.png" alt="#"></li>
																			</ul>
																		</div>
																		<p class="nftmax-chatbox__time  nftmax-chatbox__time-two">Sat 5:15 AM</p>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
														</ul>
													</div>
													<!-- End Outgoing List -->
													
													<div class="nftmax-chatbox__archive-date"><span class="nftmax-chatbox__archive-time">August 22</span></div>
													
													<!-- Incomming List -->
													<div class="nftmax-chatbox__incoming">
														<ul class="nftmax-chatbox__incoming-list">
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__main-content nftmax-chatbox__only-text">
																		<div class="nftmax-chatbox__incoming-chat">
																			<p class="nftmax-chatbox__incoming-text">Hey ! Bro sorry for late reply</p>
																		</div>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
															<!-- Single Incoming -->
															<li>
																<div class="nftmax-chatbox__chat">
																	<div class="nftmax-chatbox__author-img">
																		<img src="assets/img/chat-author11.png" alt="#">
																	</div>
																	<div class="nftmax-chatbox__main-content">
																		<div class="nftmax-chatbox__incoming-chat">
																			<p class="nftmax-chatbox__incoming-text">Didn't I tell you not to put your phone on charge just because it's the weekend?</p>
																		</div>
																		<p class="nftmax-chatbox__time nftmax-chatbox__time-two">Sat 5:15 AM</p>
																	</div>
																</div>
															</li>
															<!-- End Single Incoming -->
														</ul>
													</div>
													<!-- End Incomming List -->
													
													<!-- Incomming List -->
													<div class="nftmax-chatbox__new-message">
														<div class="nftmax-chatbox__form">
															<form class="nftmax-chatbox__form-inner" action="#">
																<input name="s" value="" type="text" placeholder="Start a new message">
																<div class="nftmax-chatbox__button">
																	<div class="nftmax-chatbox__button-inline">
																		<div class="nftmax-chatbox__btn nftmax-chatbox__imoji"><a href="#"><i class="fa-solid fa-face-smile"></i></a></div>
																		<div class="nftmax-chatbox__btn nftmax-chatbox__link"><a href="#"><i class="fa-solid fa-link"></i></a></div>
																	</div>
																	<div class="nftmax-chatbox__submit">
																		<button class="nftmax-chatbox__submit-btn" type="submit">Send<i class="fa-solid fa-paper-plane"></i></button>
																	</div>
																</div>
															</form>
														</div>
													</div>
													<!-- End Incomming List -->
													
												</div>
												
											</div>
										</div>
									</div>
								</div>
									
								</div>
								<!-- End Dashboard Inner -->
							</div>
						</div>
@include('Layout.RightSidebar')
@include('Layout.Footer')