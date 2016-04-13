<script type="text/template" data-grid="currency" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td>
				<%= r.name %>
				<% if (r.unit == '1') { %>
					<span class="label label-primary">Primary</span>
				<% } %>
			</td>
			<td><%= r.code %></td>
			<td><%= r.unit %></td>
			<td><%= r.symbol %></td>
			<td><%= r.format %></td>
			<td><%= r.short_format %></td>
			<td><%= r.locale %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
