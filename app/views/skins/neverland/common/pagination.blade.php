@if ($paginator->getLastPage() > 1)
	<div class="row-fluid align-center">
		<div class="span12">
			<div class="pagination">
				<ul>
					@if ($paginator->getCurrentPage() <= 1)
						<li class="disabled"><span>&laquo;</span></li>
					@else
						<li>{{ link_to($paginator->getUrl($paginator->getCurrentPage() - 1), '&laquo;') }}</li>
					@endif

					@for ($page = 1; $page <= $paginator->getLastPage(); $page++)
						@if ($paginator->getCurrentPage() == $page)
							<li class="active"><span>{{ $page }}</span></li>
						@else
							<li>{{ link_to($paginator->getUrl($page), $page) }}</li>
						@endif
					@endfor

					@if ($paginator->getCurrentPage() >= $paginator->getLastPage())
						<li class="disabled"><span>&raquo;</span></li>
					@else
						<li>{{ link_to($paginator->getUrl($paginator->getCurrentPage() + 1), '&raquo;') }}</li>
					@endif
				</ul>
			</div>
		</div>
	</div>
@endif
