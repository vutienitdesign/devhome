<h2>[301] REDIRECTS</h2>

<?php if (!empty($msg)) { echo $msg; } ?>

<div class="wrap form-wrap">
	<form action="" method="post" accept-charset="utf-8" style="margin: 10px 0;">
		<input type="button" name="submit" class="button import_excel" value="Import Excel">

		<input type="submit" name="submit" class="button" value="Export Current POKA Redirect's to Excel">
		
		<input type="submit" name="submit" class="button clear_redirect" onclick="return confirm('Are you sure you want to Clear Data Redirects?');" value="Clear Redirect List (DO SO WITH CAUTION!)">
	</form>

	<div class="import-data-redirects">
		<form action="" method="post" enctype="multipart/form-data">
			<input type="file" name="file-redirect" id="file-redirect">
			<p class="note"><small>Alow extension: .xls .xlsx</small></p>
			<div class="edit-tag-actions">
				<input type="submit" name="submit" class="button button-primary" value="Import Data">
			</div>
		</form>
	</div>

	<div class="add-a-redirect">
		<form  method="post" action="" id="form-addnew">
		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th scope="col" width="40%">
						<label for="request">Request</label>
						<p class="description"><small>example: /about.htm</small></p>
					</th>
			
					<th scope="col" width="10%"></th>

					<th scope="col" width="40%">
						<label for="destination">Destination</label>
						<p class="description"><small>example: http://localhost/hoidapphapluat.vn/about/</small></p>
					</th>
				</tr>

				<tr class="form-field">
					<td><input name="request" id="request" type="text">
					<td>»</td>
					<td><input name="destination" id="destination" type="text" >
					
				</tr>
				
				
			</tbody>
		</table>

		<div class="edit-tag-actions">
			<input type="submit" name="submit" class="button button-primary" value="Save Redirect">
		</div>

		</form>
	</div>
	
	<a href="javascript:void(0)" class="open-document">Documentation</a>
	<div class="documentation">
		<h3>Simple Redirects</h3>
		<p>Simple redirects work similar to the format that Apache uses: the request should be relative to your WordPress root. The destination can be either a full URL to any page on the web, or relative to your WordPress root.</p>
		<h4>Example</h4>
		<ul>
			<li><strong>Request:</strong> /old-page/</li>
			<li><strong>Destination:</strong> /new-page/</li>
		</ul>
		
		<h3>Wildcards</h3>
		<p>To use wildcards, put an asterisk (*) after the folder name that you want to redirect.</p>
		<h4>Example</h4>
		<ul>
			<li><strong>Request:</strong> /old-folder/*</li>
			<li><strong>Destination:</strong> /redirect-everything-here/</li>
		</ul>

		<p>You can also use the asterisk in the destination to replace whatever it matched in the request if you like. Something like this:</p>
		<h4>Example</h4>
		<ul>
			<li><strong>Request:</strong> /old-folder/*</li>
			<li><strong>Destination:</strong> /some/other/folder/*</li>
		</ul>
		<p>Or:</p>
		<ul>
			<li><strong>Request:</strong> /old-folder/*/content/</li>
			<li><strong>Destination:</strong> /some/other/folder/*</li>
		</ul>
	</div>
</div>

	