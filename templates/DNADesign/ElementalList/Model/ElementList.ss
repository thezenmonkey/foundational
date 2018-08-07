<div class="grid-container">
	<% if $ShowTitle %>
	    <h2 class="list-element__title">$Title</h2>
	<% end_if %>
	<div class="list-element__container $GridDirection <% if $BlockGrid %>$BlockGridClasses<% end_if %>  $VerticalAlignment $HorizontalAlignment $AlignCenterMiddle" data-listelement-count="$Elements.Elements.Count">
		$Elements
	</div>
</div>