<div id="tpl-panel" style="display:none">
	<div class="info-panel-label" style="color:#FFFFFF">
		<table id="table_id" width="100%">
			<tr>
				<td width="15%">Template</td>
				<td colspan="3"><select id="cbtpl" name="cbtpl" onchange="chgtpl(this.value)"></select></td>
			</tr>
			<tr>
				<td width="15%">Fecha</td>
				<td width="35%"><div id="lblFecha"></div></td>
				<td width="15%">Tipo</td>
				<td width="35%"><div id="lblTipo"></div></td>
			</tr>
			<tr style="height:40px; vertical-align:middle">
				<td colspan="4" align="center"><strong>Preview</strong></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><div id="tplpreview" style="width:680px; height:300px; overflow-y: auto;"></div></td>
			</tr>
			<tr>
				<td colspan="4" align="right"><button id="btnloadtpl" class="gjs-btn-prim gjs-btn-import">Cargar en el Editor</button> </td>
			</tr>
		</table>
		<br/><br/>
	</div>
</div>