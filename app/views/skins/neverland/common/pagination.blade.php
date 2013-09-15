{? $presenter = new Illuminate\Pagination\BootstrapPresenter($paginator) ?}

@if ($paginator->getLastPage() > 1)
	<div class="row-fluid align-center">
		<div class="span12">
			<div class="pagination">
				<ul>
					{{ $presenter->render() }}
				</ul>
			</div>
		</div>
	</div>
@endif
