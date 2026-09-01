@if(is_admin())
	<img class="img-preview" src="{{ get_stylesheet_directory_uri() }}/Blocks/PAListPosts/preview-{{ $block['slug'] }}.png"  alt='{{ __('Illustrative image of the front end of the block.', 'iasd-noticias') }}'/>
@else
	<div class="pa-widget pa-w-list-posts col mb-5 col-md-4">
		@notempty($title)
			<h2>{!! $title !!}</h2>
		@endnotempty

		@notempty($items)
			<div class="mt-4">
				@foreach($items as $item)
					@php $format = getPostFormat($item); @endphp

					<div class="card mb-5 mb-xl-4 border-0">
						<a href="{{ get_permalink($item) }}" title="{!! wp_strip_all_tags(get_the_title($item)) !!}">
							<div class="row">
								@if(has_post_thumbnail($item))
									<div class="img-container">
										<div class="ratio ratio-21x13">
											<figure class="figure m-xl-0">
												<img 
													class="figure-img img-fluid rounded m-0"
													src="{{ get_the_post_thumbnail_url($item, 'medium') }}"
													alt="{{ get_the_title($item) }}" 
												/>
											</figure>
										</div>
									</div>
								@endif
								
								<div class="col">
									<div class="card-body{{ has_post_thumbnail($item) ? ' p-0' : ' ps-4 pe-0 border-start border-5 pa-border' }}">
										@notempty($format)
											<span class="pa-tag text-uppercase d-none d-xl-table-cell rounded">{{ $format->name }}</span>
										@endnotempty

										<h3 class="card-title mt-xl-2 mb-0 h6 fw-bold pa-truncate-2">{!! wp_strip_all_tags(get_the_title($item)) !!}</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
				@endforeach
			</div>
		@endnotempty

		@notempty($enable_link)
			<a 
				href="{{ $link['url'] ?? '#' }}" 
				target="{{ $link['target'] ?? '_self' }}"
				class="pa-all-content"
			>
				{!! $link['title'] !!}
			</a>
		@endnotempty
	</div>
@endif