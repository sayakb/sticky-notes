{? $presenter = new Illuminate\Pagination\BootstrapPresenter($paginator) ?}

@if ($paginator->getLastPage() > 1)
	<div class="row text-center">
		<div class="col-sm-12">
			<ul class="pagination">
				{{ $presenter->render() }}
			</ul>
		</div>
	</div>
@endif
