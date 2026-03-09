@include('Layout.header')
			<!-- NFTmax Dashboard -->
			<section class="nftmax-adashboard nftmax-show">
				<div class="container">
					<div class="row">	
						<div class="col-lg-12 col-12">
							<div class="nftmax-body">
								<!-- Dashboard Inner -->
								<div class="nftmax-dsinner">
									<!-- All Notification Heading -->
									<div class="nftmax-inner__heading">
										<h2 class="nftmax-inner__page-title">Configuración</h2>
									</div>
									<!-- End All Notification Heading -->
								
									<div class="nftmax-personals">
										<h2 class="nftmax-personals__title">Información</h2>
										<div class="row">
											<div class="col-lg-3 col-md-2 col-12 nftmax-personals__list">
												<div class="nftmax-psidebar">
													<!-- Features Tab List -->
													<div class="list-group nftmax-psidebar__list" id="list-tab" role="tablist">
														<a class="list-group-item active" data-bs-toggle="list" href="#id1" role="tab"><span class="nftmax-psidebar__icon"><svg width="15" height="20" viewBox="0 0 15 20" class="fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M10.8692 11.6667H4.13085C3.03569 11.668 1.98576 12.1036 1.21136 12.878C0.436961 13.6524 0.00132319 14.7023 0 15.7975V20H15.0001V15.7975C14.9987 14.7023 14.5631 13.6524 13.7887 12.878C13.0143 12.1036 11.9644 11.668 10.8692 11.6667Z"></path><path d="M7.49953 10C10.261 10 12.4995 7.76145 12.4995 5.00002C12.4995 2.23858 10.261 0 7.49953 0C4.7381 0 2.49951 2.23858 2.49951 5.00002C2.49951 7.76145 4.7381 10 7.49953 10Z"></path></svg></span><span class="nftmax-psidebar__title">Cuenta</span></a>
														<a class="list-group-item" data-bs-toggle="list" href="#id2" role="tab"><span class="nftmax-psidebar__icon"><svg width="19" height="14" viewBox="0 0 19 14" class="fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M14.5258 0H3.82277C1.71401 0.00346265 0.00346265 1.71055 0 3.82277H18.3451C18.3451 1.71401 16.6346 0.00346265 14.5258 0Z"></path><path d="M0 9.93807C0.00346265 12.0468 1.71055 13.7574 3.82277 13.7608H14.5258C16.6346 13.7574 18.3451 12.0503 18.3486 9.93807V5.35352H0V9.93807ZM5.3498 9.55718C5.3498 10.1908 4.83733 10.7033 4.20366 10.7033C3.57 10.7033 3.05752 10.1908 3.05752 9.55718C3.05752 8.92351 3.57 8.41104 4.20366 8.41104C4.83733 8.41104 5.3498 8.92351 5.3498 9.55718Z"></path></svg></span><span class="nftmax-psidebar__title">Financiero</span></a>
														<a class="list-group-item" data-bs-toggle="list" href="#id5" role="tab"><span class="nftmax-psidebar__icon"><svg class="inline fill-current" width="18" height="21" viewBox="0 0 18 21" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z"></path></svg></span><span class="nftmax-psidebar__title">Contaseña</span></a>
														<a class="list-group-item" data-bs-toggle="list" href="#id3" role="tab"><span class="nftmax-psidebar__icon"><svg class="inline fill-current" width="18" height="21" viewBox="0 0 18 21" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z"></path></svg></span><span class="nftmax-psidebar__title">Conexiones</span></a>
														<a class="list-group-item" data-bs-toggle="list" href="#id7" role="tab"><span class="nftmax-psidebar__icon"><svg width="17" height="21" viewBox="0 0 17 21" class="fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M9.10101 0C9.10101 1.3622 9.10101 2.64618 9.10101 3.93017C9.10101 4.65363 9.29915 4.84916 10.0256 4.84916C11.3267 4.84916 12.6278 4.84916 13.9685 4.84916C13.9685 7.41061 13.9685 9.91992 13.9685 12.4879C12.4231 12.3706 11.0427 12.7356 9.90016 13.798C8.77739 14.8473 8.30847 16.1639 8.34149 17.7216C8.11033 17.7346 7.9122 17.7542 7.71406 17.7542C5.43551 17.7542 3.16356 17.7542 0.885004 17.7477C0.20474 17.7542 0 17.5456 0 16.8547C0 11.5885 0 6.32216 0 1.05587C0 0.156425 0.158508 0 1.07653 0C3.55322 0 6.03652 0 8.51981 0C8.68493 0 8.85004 0 9.10101 0ZM6.99417 8.1406C8.09052 8.1406 9.19347 8.1406 10.2898 8.1406C10.8512 8.1406 11.1946 7.89944 11.155 7.50838C11.1088 7.01955 10.7653 6.8892 10.3228 6.89572C9.35198 6.90223 8.38772 6.8892 7.41686 6.8892C6.16861 6.8892 4.92036 6.8892 3.67211 6.89572C3.0843 6.90223 2.79371 7.18249 2.88617 7.63222C2.97203 8.03631 3.27584 8.13408 3.63908 8.13408C4.76185 8.1406 5.87801 8.1406 6.99417 8.1406ZM7.0272 10.1676C5.91103 10.1676 4.79487 10.1741 3.67871 10.1676C3.2296 10.1676 2.88617 10.311 2.88617 10.8063C2.88617 11.2886 3.2296 11.3994 3.65229 11.3994C5.88462 11.3929 8.11694 11.3929 10.3559 11.3994C10.8116 11.3994 11.1682 11.2495 11.155 10.7737C11.1418 10.2914 10.772 10.1676 10.3228 10.1741C9.2265 10.1806 8.13015 10.1676 7.0272 10.1676ZM5.13831 14.6974C5.66006 14.6974 6.18182 14.7039 6.70357 14.6909C7.11305 14.6844 7.39705 14.5019 7.40365 14.0717C7.41026 13.635 7.12626 13.4655 6.71018 13.4655C5.66667 13.4721 4.62315 13.4655 3.58625 13.4655C3.17016 13.4655 2.88617 13.6285 2.88617 14.0652C2.89277 14.4888 3.17016 14.6844 3.57964 14.6844C4.09479 14.7039 4.61655 14.6974 5.13831 14.6974ZM5.01282 4.84264C5.49495 4.84264 5.98368 4.84916 6.46581 4.84264C6.84887 4.83613 7.13287 4.65363 7.14608 4.25605C7.15268 3.83892 6.8819 3.61732 6.46581 3.61732C5.49495 3.6108 4.52409 3.6108 3.55322 3.61732C3.16356 3.61732 2.89938 3.80633 2.87957 4.20391C2.85975 4.6406 3.15035 4.82961 3.55322 4.84264C4.04196 4.85568 4.52409 4.84264 5.01282 4.84264Z"></path><path d="M9.60305 17.3306C9.60965 15.3166 11.274 13.6937 13.3148 13.7002C15.3424 13.7067 17.0133 15.3687 17.0001 17.3697C16.9869 19.3706 15.3093 21.0065 13.2752 21C11.2476 20.9935 9.59644 19.3445 9.60305 17.3306ZM12.8062 17.5065C12.6147 17.3306 12.4892 17.2263 12.3703 17.1155C12.0731 16.8222 11.7363 16.77 11.4391 17.0699C11.1287 17.3827 11.2344 17.7021 11.525 17.9889C11.7495 18.2105 11.9675 18.4386 12.1986 18.6537C12.6213 19.0578 12.9383 19.0643 13.3544 18.6602C13.863 18.1713 14.3517 17.6695 14.8602 17.1807C15.1772 16.8743 15.3622 16.555 14.9857 16.19C14.6291 15.838 14.279 15.9684 13.9686 16.2943C13.5856 16.6853 13.2091 17.0894 12.8062 17.5065Z"></path><path d="M10.3955 3.56557C10.3955 2.43801 10.3955 1.32349 10.3955 0.228516C11.5183 1.34956 12.6344 2.46408 13.7374 3.56557C12.6873 3.56557 11.5579 3.56557 10.3955 3.56557Z"></path></svg></span><span class="nftmax-psidebar__title">Términos </span></a>
													</div>
												</div>
											</div>
											
											<div class="col-lg-12 col-md-12 col-12  nftmax-personals__content">
												<div class="nftmax-ptabs">
												
													<div class="nftmax-ptabs__inner">
														<div class="tab-content" id="nav-tabContent">
															<!--  Features Single Tab -->
															<div class="tab-pane fade show active" id="id1" role="tabpanel">
																<form action="#">
																	<div class="row">
																		<div class="col-12">
																			<div class="nftmax-ptabs__separate">
																				<div class="nftmax-ptabs__form-main">
																					<div class="nftmax__item-form--group">
																						<div class="row">
																							<div class="col-lg-12 col-12">
																								<div class="nftmax__item-form--group nftmax-last-name">
																									<label class="nftmax__item-label">Nombre </label>
																									<input class="nftmax__item-input" type="text" 
                                                                                                    value = "{{ $User_personal_Info['first_name'] }}"
                                                                                                    required="required">
																								</div>
																							</div>	
																							
																						</div>	
																					</div>	

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Email </label>
																						<input class="nftmax__item-input" type="email" value = "{{ $User_personal_Info['email'] }}" required="required">
																						
																					</div>

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Celular</label>
																						<input class="nftmax__item-input" type="text" value = "" required="required">
																					</div>

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Tipo Identificación</label>
																						<input class="nftmax__item-input" type="text" value = "" required="required">
																					</div>

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Identificación</label>
																						<input class="nftmax__item-input" type="text" value = "" required="required">
																					</div>


																					
																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Usuario</label>
																						<input class="nftmax__item-input" type="url" value = "{{ $User_personal_Info['user_name'] }}" required="required">
																					</div>
																					
																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Link </label>
																						<input class="nftmax__item-input" type="url" placeholder="https:yoursite.lo/imte/item_name123" required="required">
																					</div>
																					
																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Descripción </label>
																						<textarea class="nftmax__item-input nftmax__item-textarea" type="url" required="required">{{ $User_personal_Info['bio'] }}</textarea>
																					</div>
																					
																					
																					
																					
																					
																				</div>
																				<div class="nftmax-ptabs__form-update">
																					<div class="nftmax-ptabs__sidebar">
																						<div class="nftmax-ptabs__ssingle nftmax-ptabs__srofile">
																							<div class="nftmax-ptabs__sheading">
																								<h4 class="nftmax-ptabs__stitle">Logo <svg width="17" height="17" viewBox="0 0 17 17" class="fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M8.5 0C3.80338 0 0 3.80622 0 8.5C0 13.1938 3.80623 17 8.5 17C13.1938 17 17 13.1938 17 8.5C16.9972 3.80907 13.1938 0.00568942 8.5 0ZM8.5 14.1695C8.10743 14.1695 7.79167 13.8537 7.79167 13.4612C7.79167 13.0686 8.10743 12.7528 8.5 12.7528C8.89257 12.7528 9.20833 13.0686 9.20833 13.4612C9.20833 13.8509 8.89257 14.1695 8.5 14.1695ZM9.86831 8.86128C9.4416 9.12868 9.19126 9.6009 9.20833 10.1016V10.6278C9.20833 11.0204 8.89257 11.3362 8.5 11.3362C8.10743 11.3362 7.79167 11.0204 7.79167 10.6278V10.1016C7.77176 9.08317 8.30371 8.13303 9.18273 7.62098C9.72038 7.32513 10.0049 6.71637 9.89107 6.11613C9.77728 5.54434 9.33066 5.09772 8.75887 4.98678C7.9908 4.84454 7.25118 5.35375 7.10894 6.12182C7.09472 6.20716 7.08618 6.2925 7.08618 6.37784C7.08618 6.77041 6.77042 7.08618 6.37785 7.08618C5.98528 7.08618 5.66952 6.77041 5.66952 6.37784C5.66952 4.81325 6.93825 3.54451 8.50569 3.54451C10.0703 3.54451 11.339 4.81325 11.339 6.38069C11.3333 7.41048 10.7729 8.36061 9.86831 8.86128Z"></path></svg></h4>
																								
																							</div>
																							<div class="nftmax-ptabs__sauthor">
																								<div class="nftmax-ptabs__sauthor-img nftmax-ptabs__pthumb">
																									<img src="{{ asset( $User_personal_Info['profile_image']) }}" alt="#">
																									 <label for="file-input"><span class="nftmax-ptabs__sedit"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.5147 11.5C17.7284 12.7137 18.9234 13.9087 20.1296 15.115C19.9798 15.2611 19.8187 15.4109 19.6651 15.5683C17.4699 17.7635 15.271 19.9587 13.0758 22.1539C12.9334 22.2962 12.7948 22.4386 12.6524 22.5735C12.6187 22.6034 12.5663 22.6296 12.5213 22.6296C11.3788 22.6334 10.2362 22.6297 9.09365 22.6334C9.01498 22.6334 9 22.6034 9 22.536C9 21.4009 9 20.2621 9.00375 19.1271C9.00375 19.0746 9.02997 19.0109 9.06368 18.9772C10.4123 17.6249 11.7609 16.2763 13.1095 14.9277C14.2295 13.8076 15.3459 12.6913 16.466 11.5712C16.4884 11.5487 16.4997 11.5187 16.5147 11.5Z" fill="white"></path><path d="M20.9499 14.2904C19.7436 13.0842 18.5449 11.8854 17.3499 10.6904C17.5634 10.4694 17.7844 10.2446 18.0054 10.0199C18.2639 9.76139 18.5261 9.50291 18.7884 9.24443C19.118 8.91852 19.5713 8.91852 19.8972 9.24443C20.7251 10.0611 21.5492 10.8815 22.3771 11.6981C22.6993 12.0165 22.7105 12.4698 22.3996 12.792C21.9238 13.2865 21.4443 13.7772 20.9686 14.2717C20.9648 14.2792 20.9536 14.2867 20.9499 14.2904Z" fill="white"></path></svg></span></label>
																									 <input id="file-input" type="file" />
																								</div>
																							</div>
																						</div>	
																						<div class="nftmax-ptabs__ssingle nftmax-ptabs__scover">
																							<div class="nftmax-ptabs__sheading">
																								<h4 class="nftmax-ptabs__stitle nftmax-ptabs__stitle--update">Banner <svg width="17" height="17" viewBox="0 0 17 17" class="fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M8.5 0C3.80338 0 0 3.80622 0 8.5C0 13.1938 3.80623 17 8.5 17C13.1938 17 17 13.1938 17 8.5C16.9972 3.80907 13.1938 0.00568942 8.5 0ZM8.5 14.1695C8.10743 14.1695 7.79167 13.8537 7.79167 13.4612C7.79167 13.0686 8.10743 12.7528 8.5 12.7528C8.89257 12.7528 9.20833 13.0686 9.20833 13.4612C9.20833 13.8509 8.89257 14.1695 8.5 14.1695ZM9.86831 8.86128C9.4416 9.12868 9.19126 9.6009 9.20833 10.1016V10.6278C9.20833 11.0204 8.89257 11.3362 8.5 11.3362C8.10743 11.3362 7.79167 11.0204 7.79167 10.6278V10.1016C7.77176 9.08317 8.30371 8.13303 9.18273 7.62098C9.72038 7.32513 10.0049 6.71637 9.89107 6.11613C9.77728 5.54434 9.33066 5.09772 8.75887 4.98678C7.9908 4.84454 7.25118 5.35375 7.10894 6.12182C7.09472 6.20716 7.08618 6.2925 7.08618 6.37784C7.08618 6.77041 6.77042 7.08618 6.37785 7.08618C5.98528 7.08618 5.66952 6.77041 5.66952 6.37784C5.66952 4.81325 6.93825 3.54451 8.50569 3.54451C10.0703 3.54451 11.339 4.81325 11.339 6.38069C11.3333 7.41048 10.7729 8.36061 9.86831 8.86128Z"></path></svg></h4>
																								
																							</div>
																							<div class="nftmax-ptabs__sauthor">
																								<div class="nftmax-ptabs__sauthor-img nftmax-ptabs__pthumb">
																									<img src="{{ $User_personal_Info['cover_image'] }}" alt="#">
																									 <label for="file-input"><span class="nftmax-ptabs__sedit"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.5147 11.5C17.7284 12.7137 18.9234 13.9087 20.1296 15.115C19.9798 15.2611 19.8187 15.4109 19.6651 15.5683C17.4699 17.7635 15.271 19.9587 13.0758 22.1539C12.9334 22.2962 12.7948 22.4386 12.6524 22.5735C12.6187 22.6034 12.5663 22.6296 12.5213 22.6296C11.3788 22.6334 10.2362 22.6297 9.09365 22.6334C9.01498 22.6334 9 22.6034 9 22.536C9 21.4009 9 20.2621 9.00375 19.1271C9.00375 19.0746 9.02997 19.0109 9.06368 18.9772C10.4123 17.6249 11.7609 16.2763 13.1095 14.9277C14.2295 13.8076 15.3459 12.6913 16.466 11.5712C16.4884 11.5487 16.4997 11.5187 16.5147 11.5Z" fill="white"></path><path d="M20.9499 14.2904C19.7436 13.0842 18.5449 11.8854 17.3499 10.6904C17.5634 10.4694 17.7844 10.2446 18.0054 10.0199C18.2639 9.76139 18.5261 9.50291 18.7884 9.24443C19.118 8.91852 19.5713 8.91852 19.8972 9.24443C20.7251 10.0611 21.5492 10.8815 22.3771 11.6981C22.6993 12.0165 22.7105 12.4698 22.3996 12.792C21.9238 13.2865 21.4443 13.7772 20.9686 14.2717C20.9648 14.2792 20.9536 14.2867 20.9499 14.2904Z" fill="white"></path></svg></span></label>
																									 <input id="file-input" type="file" />
																								</div>
																							</div>
																						</div>	
																					</div>	
																					
																					
																				</div>
																			</div>
																		</div>
																	</div>
																	
																	
																	<div class="nftmax__item-button--group nftmax__ptabs-bottom">
																		<button class="nftmax__item-button--single nftmax__item-button--cancel">Cancel</button>
																		<a class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius " href="{{ url('/my-profile') }}" type="submit">Update Profile</a>
																	</div>
																</form>
															</div>
															<div class="tab-pane fade show active" id="id2" role="tabpanel">
																<form action="#">
																	<div class="row">
																		<div class="col-12">
																			<div class="nftmax-ptabs__separate">
																				<div class="nftmax-ptabs__form-main">
																					<div class="nftmax__item-form--group">
																						<div class="row">
																							<div class="col-lg-6 col-12">
																								<div class="nftmax__item-form--group nftmax-last-name">
																									<label class="nftmax__item-label">Tipo persona </label>
																									<input class="nftmax__item-input" type="text" 
                                                                                                    value = "{{ $User_personal_Info['first_name'] }}"
                                                                                                    required="required">
																								</div>
																							</div>	
																							
																						</div>	
																					</div>	

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Responsabilidad tributaria </label>
																						<input class="nftmax__item-input" type="email" value = "{{ $User_personal_Info['email'] }}" required="required">
																						
																					</div>

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">País</label>
																						<input class="nftmax__item-input" type="text" value = "" required="required">
																					</div>

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Municipio</label>
																						<input class="nftmax__item-input" type="text" value = "" required="required">
																					</div>

																					<div class="nftmax__item-form--group">
																						<label class="nftmax__item-label">Dirección</label>
																						<input class="nftmax__item-input" type="text" value = "" required="required">
																					</div>


																					
																					
																					
																					
																					
																					
																					
																				</div>
																		
																			</div>
																		</div>
																	</div>
																	
																	
																	<div class="nftmax__item-button--group nftmax__ptabs-bottom">
																		<button class="nftmax__item-button--single nftmax__item-button--cancel">Cancel</button>
																		<a class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius " href="{{ url('/my-profile') }}" type="submit">Update Profile</a>
																	</div>
																</form>
															</div>
															
															<div class="tab-pane fade" id="id3" role="tabpanel">
																<form action="#">
																	<div class="nftmax-paymentm">
																		<ul class="nftmax-paymentm__list nftmax-paymentm__list--notify pt-0">
																		
																			<li class="nftmax-paymentm__single nftmax-paymentm__single--notify">
																				<div class="nftmax-paymentm__name">
																					<div class="nftmax-paymentm__icon nftmax-paymentm__icon--notify ntfmax__bgc--1">
																						<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="30" fill="url(#paint0_linear_41_74037)"></circle><path d="M29.9703 43.9959C27.4494 43.9959 24.9286 44.0078 22.4077 43.9919C20.2721 43.9799 19.0338 42.7656 19.0197 40.6347C18.9955 37.2735 18.9915 33.9123 19.0197 30.5511C19.0378 28.4521 20.3265 27.2158 22.4481 27.2118C27.4212 27.2019 32.3964 27.2039 37.3695 27.2118C39.604 27.2158 40.8564 28.4641 40.8644 30.6909C40.8745 33.9862 40.8786 37.2815 40.8644 40.5767C40.8544 42.7616 39.6242 43.9819 37.43 43.9939C34.9434 44.0058 32.4569 43.9959 29.9703 43.9959ZM27.5321 33.9722C27.6108 34.1699 27.6592 34.789 27.9919 35.0466C28.8954 35.7476 28.7542 36.6544 28.7563 37.567C28.7563 37.9984 28.6272 38.5057 28.8107 38.8412C29.0467 39.2706 29.5367 39.8358 29.9219 39.8398C30.3131 39.8438 30.8496 39.3026 31.0613 38.8752C31.261 38.4698 31.1319 37.9006 31.1319 37.4053C31.1319 36.5525 31.0936 35.7716 31.868 35.0706C32.6041 34.4036 32.4871 33.1354 31.8841 32.3086C31.2811 31.4817 30.2002 31.1262 29.2019 31.4258C28.2521 31.7114 27.5483 32.6621 27.5321 33.9722Z" fill="white"></path><path d="M37.5315 26.2691C36.3356 26.2691 35.2466 26.2691 34.0345 26.2691C34.0345 25.4044 34.0446 24.5856 34.0325 23.7647C33.9982 21.3202 32.2397 19.4709 29.9467 19.4589C27.6497 19.4469 25.8851 21.2803 25.8448 23.7368C25.8306 24.5596 25.8427 25.3844 25.8427 26.2672C24.6448 26.2672 23.5558 26.2672 22.4729 26.2672C21.7126 21.3322 23.5054 17.6674 27.7546 16.3473C32.5603 14.8535 37.3984 18.3624 37.5274 23.3493C37.5516 24.304 37.5315 25.2606 37.5315 26.2691Z" fill="white"></path><defs><linearGradient id="paint0_linear_41_74037" x1="0" y1="0" x2="63.3708" y2="62.0225" gradientUnits="userSpaceOnUse"><stop stop-color="#F539F8"></stop><stop offset="0.416763" stop-color="#C342F9"></stop><stop offset="1" stop-color="#5356FB"></stop></linearGradient></defs></svg>
																					</div>
																					<div class="nftmax-paymentm__content">
																						<h4 class="nftmax-paymentm__title nftmax-paymentm__title--notify">Alegra</h4>
																						<p class="nftmax-paymentm__text nftmax-paymentm__text--notify">Códgo 134</p>
																					</div>
																				</div>
																				<div class="nftmax-ptabs__notify-switch  nftmax-ptabs__notify-switch--two">
																					<label class="nftmax__item-switch">
																					  <input type="checkbox" checked="">
																					  <span class="nftmax__item-switch--slide nftmax__item-switch--round"></span>
																					</label>
																				</div>
																			</li>	
																			
																			<li class="nftmax-paymentm__single nftmax-paymentm__single--notify">
																				<div class="nftmax-paymentm__name">
																					<div class="nftmax-paymentm__icon nftmax-paymentm__icon--notify ntfmax__bgc--2">
																						<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="30" fill="#5356FB"></circle><path d="M29.9407 28.117C29.3978 27.9377 28.8874 27.8356 28.4342 27.6115C24.4201 25.6243 20.4135 23.6173 16.4044 21.6202C16.1106 21.4733 15.884 21.2841 15.879 20.933C15.874 20.5669 16.1006 20.3627 16.4094 20.2083C20.3911 18.2237 24.3728 16.2341 28.352 14.2445C29.4974 13.6718 30.6255 13.7365 31.756 14.3068C34.3133 15.5942 36.8806 16.8691 39.443 18.149C40.7503 18.8014 42.0551 19.4563 43.3599 20.1112C43.4346 20.1486 43.5093 20.1834 43.5865 20.2208C43.8853 20.3602 44.092 20.5769 44.092 20.923C44.092 21.2741 43.8704 21.4708 43.5766 21.6153C41.7214 22.5391 39.8688 23.4679 38.0136 24.3917C35.8721 25.46 33.7406 26.5432 31.5866 27.5841C31.0737 27.8331 30.4935 27.9426 29.9407 28.117Z" fill="white"></path><path d="M28.9083 38.6004C28.9083 40.8266 28.9108 43.0527 28.9058 45.2789C28.9033 46.0707 28.4576 46.3496 27.728 46.0135C24.0775 44.3326 20.4245 42.6568 16.7814 40.9635C15.6086 40.4182 15.0109 39.4645 15.006 38.1746C14.996 33.9314 15.001 29.6883 15.0035 25.4451C15.0035 24.6259 15.4641 24.3221 16.1962 24.6607C19.9713 26.4013 23.7438 28.1519 27.5213 29.885C28.48 30.3257 28.9232 31.0429 28.9158 32.0912C28.9008 34.2626 28.9108 36.4315 28.9108 38.6029C28.9108 38.6004 28.9108 38.6004 28.9083 38.6004Z" fill="white"></path><path d="M31.0596 38.5382C31.0596 36.3544 31.0646 34.1705 31.0571 31.9867C31.0546 31.0006 31.5054 30.3208 32.3894 29.9124C36.1893 28.1594 39.9917 26.4113 43.7916 24.6608C44.4839 24.342 44.9595 24.6284 44.9595 25.3829C44.9644 29.6684 44.9719 33.9514 44.9545 38.2369C44.9495 39.5492 44.2772 40.4655 43.0944 41.0134C40.1411 42.378 37.1853 43.74 34.232 45.1021C33.5995 45.3935 32.9695 45.6848 32.3395 45.9762C31.4829 46.3696 31.0571 46.1032 31.0571 45.1719C31.0571 42.9606 31.0571 40.7469 31.0571 38.5357C31.0596 38.5382 31.0596 38.5382 31.0596 38.5382Z" fill="white"></path></svg>
																					</div>
																					<div class="nftmax-paymentm__content">
																						<h4 class="nftmax-paymentm__title nftmax-paymentm__title--notify">Bitrix24</h4>
																						<p class="nftmax-paymentm__text nftmax-paymentm__text--notify">Código 2378</p>
																					</div>
																				</div>
																				<div class="nftmax-ptabs__notify-switch nftmax-ptabs__notify-switch--two">
																					<label class="nftmax__item-switch">
																					  <input type="checkbox" checked="">
																					  <span class="nftmax__item-switch--slide nftmax__item-switch--round"></span>
																					</label>
																				</div>
																			</li>	
																			<li class="nftmax-paymentm__single nftmax-paymentm__single--notify">
																				<div class="nftmax-paymentm__name">
																					<div class="nftmax-paymentm__icon nftmax-paymentm__icon--notify ntfmax__bgc--3">
																						<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="30" fill="#F539F8"></circle><path d="M41.8287 28.0141C41.4077 29.4703 41.0646 30.8583 40.5811 32.1937C40.4622 32.5232 39.8851 32.8897 39.5147 32.8936C35.1811 32.9443 30.8474 32.9267 26.5138 32.9287C25.8724 32.9287 25.463 32.6129 25.3168 31.9988C24.6306 29.1292 23.9385 26.2596 23.2835 23.3841C23.0847 22.5146 23.4804 21.9785 24.3187 21.9727C27.8511 21.9493 31.3835 21.9629 35.0037 21.9629C34.9608 23.8383 35.4716 25.4778 36.8927 26.7274C38.2827 27.9575 39.9261 28.324 41.8287 28.0141Z" fill="white"></path><path d="M24.4176 33.9331C27.334 33.9331 30.1997 33.9331 33.0654 33.9331C34.9408 33.9331 36.8162 33.9292 38.6915 33.9351C39.5649 33.937 40.0601 34.3289 40.0835 35.0053C40.1069 35.7169 39.5961 36.1497 38.6877 36.1497C33.7224 36.1536 28.759 36.1536 23.7938 36.1497C22.7878 36.1497 22.5091 35.904 22.2888 34.8884C21.3452 30.5255 20.3939 26.1645 19.4679 21.7977C19.3587 21.285 19.1579 20.9965 18.6296 20.8347C17.419 20.4643 16.2318 20.0159 15.0368 19.5948C14.0328 19.24 13.6605 18.6981 13.9334 18.0021C14.2024 17.314 14.7853 17.1385 15.7386 17.4699C17.2221 17.9846 18.692 18.5382 20.1912 19.0061C20.9476 19.242 21.3082 19.6689 21.4681 20.4331C22.3824 24.7746 23.3317 29.1082 24.2733 33.4438C24.3006 33.5725 24.3474 33.7012 24.4176 33.9331Z" fill="white"></path><path d="M35.6934 22.6285C35.6798 19.9227 37.8729 17.7237 40.5788 17.7334C43.23 17.7412 45.4134 19.9441 45.4095 22.609C45.4056 25.2662 43.2144 27.4632 40.5573 27.469C37.9022 27.4749 35.7071 25.2915 35.6934 22.6285ZM42.6257 20.4685C41.7465 21.4101 40.8848 22.3342 39.8614 23.4317C39.3721 22.7669 38.9763 22.2328 38.524 21.6207C38.0698 22.0963 37.7696 22.4102 37.5747 22.6168C38.4051 23.4746 39.1986 24.2914 39.9316 25.0498C41.1207 23.8567 42.345 22.6305 43.6726 21.299C43.4562 21.1274 43.1969 20.9208 42.9357 20.7142C42.8538 20.6459 42.77 20.5835 42.6257 20.4685Z" fill="white"></path><path d="M37.4854 37.0509C39.2068 37.0353 40.6104 38.3882 40.6436 40.096C40.6767 41.8329 39.2653 43.258 37.5147 43.258C35.805 43.258 34.4072 41.868 34.3994 40.1662C34.3936 38.4565 35.7699 37.0685 37.4854 37.0509ZM38.8929 40.1447C38.891 39.3903 38.2984 38.7781 37.5537 38.7625C36.7817 38.745 36.1384 39.3825 36.1423 40.1603C36.1462 40.907 36.7485 41.5269 37.4854 41.5464C38.2574 41.5659 38.8968 40.9303 38.8929 40.1447Z" fill="white"></path><path d="M24.8751 43.2571C23.1556 43.2434 21.7754 41.8613 21.7793 40.1555C21.7852 38.3951 23.179 37.0286 24.9491 37.0481C26.6569 37.0676 28.0273 38.4751 28.0078 40.1886C27.9864 41.9022 26.5906 43.2688 24.8751 43.2571ZM26.2709 40.1574C26.2728 39.3738 25.6334 38.7441 24.8556 38.7636C24.1226 38.7831 23.4968 39.4167 23.4968 40.1458C23.4948 40.8768 24.1148 41.5182 24.8439 41.5435C25.6178 41.5708 26.2689 40.9372 26.2709 40.1574Z" fill="white"></path></svg>
																					</div>
																					<div class="nftmax-paymentm__content">
																						<h4 class="nftmax-paymentm__title nftmax-paymentm__title--notify">Cotizador</h4>
																						<p class="nftmax-paymentm__text nftmax-paymentm__text--notify">Código 2098</p>
																					</div>
																				</div>
																				<div class="nftmax-ptabs__notify-switch nftmax-ptabs__notify-switch--two">
																					<label class="nftmax__item-switch">
																					  <input type="checkbox" checked="">
																					  <span class="nftmax__item-switch--slide nftmax__item-switch--round"></span>
																					</label>
																				</div>
																			</li>	
																				
																		</ul>	
																	</div>	
																</form>
															</div>
															<div class="tab-pane fade" id="id4" role="tabpanel">
																<form action="#">
																	<div class="nftmax-personals__history">	
																		<table id="nftmax-table__main" class="nftmax-table__main nftmax-table__main--profile">
																			<thead class="nftmax-table__head">
																				<tr>
																					<th class="nftmax-table__column-1 nftmax-table__h1">OS</th>
																					<th class="nftmax-table__column-2 nftmax-table__h2">Browser</th>
																					<th class="nftmax-table__column-3 nftmax-table__h3">Location </th>
																					<th class="nftmax-table__column-4 nftmax-table__h4">Last Session</th>
																					<th class="nftmax-table__column-5 nftmax-table__h5">Status</th>
																					<th class="nftmax-table__column-6 nftmax-table__h6"></th>
																				</tr>
																			</thead>
																			<tbody class="nftmax-table__body">
																			@foreach($LoginActivity as $LoginActivity)
																				<tr>
																					<td class="nftmax-table__column-1 nftmax-table__data-1">
																						<div class="nftmax-table__product-content">
																							<p class="nftmax-table__product-desc">{{ $LoginActivity['0'] }}</p>
																						</div>
																					</td>
																					<td class="nftmax-table__column-2 nftmax-table__data-2">
																						<div class="nftmax-table__product-content">
																							<p class="nftmax-table__product-desc">{{ $LoginActivity['1'] }}</p>
																						</div>
																					</td>
																					<td class="nftmax-table__column-3 nftmax-table__data-3">
																						<div class="nftmax-table__product-content">
																							<p class="nftmax-table__product-desc">{{ $LoginActivity['2'] }}</p>
																						</div>
																					</td>
																					<td class="nftmax-table__column-4 nftmax-table__data-4">
																						<div class="nftmax-table__product-content">
																							<p class="nftmax-table__product-desc">{{ $LoginActivity['3'] }}</p>
																						</div>
																					</td>
																					<td class="nftmax-table__column-5 nftmax-table__data-5">
																						<div class="nftmax-table__product-content">
																							<div class="nftmax-table__status__group">
																								@if($LoginActivity[4] == 1)
																								<div class="nftmax-table__status nftmax-gbcolor">Active</div>
																								@else
																								<div class="nftmax-table__status nftmax-rbcolor">Unactive</div>
																								@endif
																							</div>
																						</div>
																					</td>
																					<td class="nftmax-table__column-6 nftmax-table__data-6">
																						<div class="nftmax-table__product-content">
																							<div class="nftmax-table__status__group">
																								<div class="nftmax-table__trash"><a href="#"><i class="fa-solid fa-trash-can"></i></a></div>
																							</div>
																						</div>
																					</td>
																				</tr>
																			@endforeach	
																			</tbody>
																		</table>
																	</div>
																</form>
															</div>
															<div class="tab-pane fade" id="id5" role="tabpanel">
																<div class="row">
																	<div class="col-lg-6 col-md-6 col-12">
																		<!-- Sign in Form -->
																		<form class="nftmax-wc__form-main nftmax-wc__form-main--profile" action="{{url('/change-password')}}" method="post">
																			@csrf
																			<div class="form-group">
																				<label class="nftmax-wc__form-label">Old Password</label>
																				<div class="form-group__input">
																					<span class="nftmax-wc__icon lock"><svg class="inline" width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
																					<span class="nftmax-wc__icon"><i class="fa-solid fa-eye"></i></span>
																					<input class="nftmax-wc__form-input" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" id="password-field" type="password" name="old_password" placeholder="" maxlength="8" required="required">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="nftmax-wc__form-label">New Password</label>
																				<div class="form-group__input">
																					<span class="nftmax-wc__icon lock"><svg class="inline" width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
																					<span class="nftmax-wc__icon"><i class="fa-solid fa-eye"></i></span>
																					<input class="nftmax-wc__form-input" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" id="password-field" type="password" name="new_password" placeholder="" maxlength="8" required="required">
																				</div>
																			</div>
																			<div class="form-group">
																				<label class="nftmax-wc__form-label">Re-enter Password</label>
																				<div class="form-group__input">
																					<span class="nftmax-wc__icon lock"><svg class="inline" width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.4467 7.1581V5.94904C14.4467 2.66455 11.7822 0 8.49771 0C5.21323 0 2.54867 2.66455 2.54867 5.94904V7.1581C1.00076 7.83194 -0.000366211 9.36059 -0.000366211 11.0471V16.149C0.0034843 18.494 1.90178 20.3961 4.25059 20.4H12.7525C15.0975 20.3961 16.9996 18.4978 17.0035 16.149V11.051C16.9919 9.36059 15.9908 7.83579 14.4467 7.1581ZM9.34482 14.451C9.34482 14.9207 8.96362 15.3019 8.49386 15.3019C8.0241 15.3019 7.6429 14.9207 7.6429 14.451V12.749C7.6429 12.2793 8.0241 11.8981 8.49386 11.8981C8.96362 11.8981 9.34482 12.2793 9.34482 12.749V14.451ZM12.7448 6.8H4.24289V5.94904C4.24289 3.60023 6.14505 1.69807 8.49386 1.69807C10.8427 1.69807 12.7448 3.60023 12.7448 5.94904V6.8Z" fill="#374557" fill-opacity="0.6"></path></svg></span>
																					<span class="nftmax-wc__icon"><i class="fa-solid fa-eye"></i></span>
																					<input class="nftmax-wc__form-input" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" id="password-field" type="password" name="confirm_password" placeholder="" maxlength="8" required="required">
																				</div>
																			</div>
																			<div class="form-group">
																				<div class="nftmax__item-button--group nftmax__item-button--group--profile">
																					<button class="nftmax__item-button--single nftmax__item-button--cancel">Cancel</button>
																					<input class="nftmax__item-button--single nftmax-btn nftmax-btn__bordered bg radius " type="submit">Change Password</input>
																				</div>
																			</div>
																		</form>	
																		<!-- End Sign in Form -->
																	</div>
																	<div class="col-lg-6 col-md-6 col-12">
																		<div class="nftmax-password__img">
																			<img src="assets/img/password-reset.png" alt="">
																		</div>
																	</div>
																</div>
															</div>
															<div class="tab-pane fade" id="id6" role="tabpanel">
																<div class="nftmax-accordion accordion accordion-flush" id="nftmax-accordion">
																	<!-- Single Accordion -->
																	@foreach($FAQ as $FAQ)
																	<div class="accordion-item nftmax-accordion__single">
																		<h2 class="accordion-header" id="nftac-1">
																			<button class="accordion-button collapsed nftmax-accordion__heading" type="button" data-bs-toggle="collapse" data-bs-target="#ac-collapseOne" aria-expanded="false" aria-controls="ac-collapseOne">{{ $FAQ['0'] }}</button>
																		</h2>
																		<div id="ac-collapseOne" class="accordion-collapse collapse" aria-labelledby="nftac-1" data-bs-parent="#nftmax-accordion">
																		  <div class="accordion-body nftmax-accordion__body">{{ $FAQ['1'] }}</div>
																		</div>
																	</div>
																	@endforeach
																	<!-- End Single Accordion -->
																</div>
															</div>
															<div class="tab-pane fade" id="id7" role="tabpanel">
																@foreach($TermsAndCondition as $TermsAndCondition)
																<br></br>
																<div class="nftmax-ptabs__page" >
																	<h3 class="nftmax-ptabs__page-title">{{ $TermsAndCondition['0'] }}. {{ $TermsAndCondition['1'] }}</h3>
																	<p>{{ $TermsAndCondition['2'] }}</p>
																</div>
																@endforeach
															</div>
															
														</div>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- End Dashboard Inner -->
							</div>
						</div>

@include('Layout.Footer')