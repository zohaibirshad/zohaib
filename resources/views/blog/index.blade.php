@extends('layouts.master')
@section('title', 'Blog')
@section('subtitle', 'Featured Posts')
@section('title_bar')
    @include('partials.title_bar')
@endsection

@section('content')
<!-- Recent Blog Posts -->
<div class="section white padding-top-0 padding-bottom-60 full-width-carousel-fix">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<div class="blog-carousel">

					@forelse($featured_posts as $post)
					<a href="{{ route('blog.show', $post->slug) }}" class="blog-compact-item-container">
						<div class="blog-compact-item">
							<img src="{{ asset('assets/images/blog-04a.jpg') }}" alt="">
							<span class="blog-item-tag">{{ $post->categories[0]->name }}</span>
							<div class="blog-compact-item-content">
								<ul class="blog-post-tags">
									<li>{{ $post->created_at }}</li>
								</ul>
								<h3>{{ $post->title }}</h3>
								<p>{{ $post->body }}</p>
							</div>
						</div>
					</a>
					@empty
					<h3>NO FEATURED POST AVAILABLE</h3>
					@endforelse

				</div>

			</div>
		</div>
	</div>
</div>
<!-- Recent Blog Posts / End -->


<!-- Section -->
<div class="section gray">
	<div class="container">
		<div class="row">
			<div class="col-xl-8 col-lg-8">

				<!-- Section Headline -->
				<div class="section-headline margin-top-60 margin-bottom-35">
					<h4>Recent Posts</h4>
				</div>

				<!-- Blog Post -->
				@forelse ($blog_posts as $post)
					<a href="blog/{{ $post->slug }}" class="blog-post">
						<!-- Blog Post Thumbnail -->
						<div class="blog-post-thumbnail">
							<div class="blog-post-thumbnail-inner">
								<span class="blog-item-tag">{{ $post->categories[0]->name }}</span>
								<img src="{{ asset('assets/images/blog-01a.jpg') }}" alt="">
							</div>
						</div>
						<!-- Blog Post Content -->
						<div class="blog-post-content">
							<span class="blog-post-date">{{ $post->created_at }}</span>
							<h3>{{ $post->title }}</h3>
							<p>{{ $post->body }}</p>
						</div>
						<!-- Icon -->
						<div class="entry-icon"></div>
					</a>
				@empty
					<h3>NO POST AVAILABLE</h3>
				@endforelse

				<!-- Pagination -->
				<div class="clearfix"></div>
				{{ $blog_posts->onEachSide(1)->links('vendor.pagination.default') }}
				<!-- Pagination / End -->

			</div>


			<div class="col-xl-4 col-lg-4 content-left-offset">
				<div class="sidebar-container margin-top-65">
					<!-- Widget -->
					<div class="sidebar-widget">

						<h3>Trending Posts</h3>
						<ul class="widget-tabs">

							<!-- Post #1 -->
							@forelse ($trending_posts as $post)
								<li>
									<a href="{{ route('blog.show', $post->slug) }}" class="widget-content active">
										<img src="{{ asset('assets/images/blog-02a.jpg') }}" alt="">
										<div class="widget-text">
											<h5>{{ $post->title }}</h5>
											<span>{{ $post->created_at }}</span>
										</div>
									</a>
								</li>	
							@empty
								
							@endforelse

						</ul>

					</div>
					<!-- Widget / End-->

					<!-- Widget -->
					<div class="sidebar-widget">
						<h3>Categories</h3>
						<div class="task-tags text-capitalize">
							@foreach ($categories as $category)
								<a href="/blog?category={{ $category->slug }}"><span>{{ $category->name }}</span></a>
							@endforeach
						</div>
					</div>


					<!-- Widget -->
					<div class="sidebar-widget">
						<h3>Tags</h3>
						<div class="task-tags text-lowercase">
							@foreach ($tags as $tag)
								<a href="/blog?tag={{ $tag->slug }}"><span>{{ $tag->name }}</span></a>
							@endforeach
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>

	<!-- Spacer -->
	<div class="padding-top-40"></div>
	<!-- Spacer -->

</div>
<!-- Section / End -->
@endsection
