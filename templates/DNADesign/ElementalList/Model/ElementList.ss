<div class="<% if not $FullWidth %>grid-contianer<% end_if %>">
	<% if $ShowTitle %>
	    <h2 class="list-element__title">$Title</h2>
	<% end_if %>
	<div class="list-element__container $GridDirection $Full<% if $BlockGrid %>$BlockGridClasses<% end_if %>" data-listelement-count="$Elements.Elements.Count">
		$Elements
	</div>
</div>