<script type="text/template" data-grid="money" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.amount %></td>
			<td><%= r.type %></td>
			<td><%= r.currency_id %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
