<?php
	$akun_kas3 = $result_akun_kas3[0];
?>


			<td align="center"><label class="control-label"><?=$akun_kas3->kd_kas_3?></label></td>
			<td align="center"><input type="text" name="nm_kas_3" id="nm_kas_3" class="nm_kas_3 validate[required] form-control" value="<?=$akun_kas3->nm_kas_3?>"></td>
			<td align="center" colspan="2" style="text-align:center">
				<div class="btn-group">
					<button type="submit" rel="<?=$akun_kas3->kd_kas_3?>" class="btn btn-default submit btn-sm" aria-label="Left Align"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
					<button type="reset" rel="<?=$akun_kas3->kd_kas_3?>" name="cancel" id="reset" class="btn btn-default cancel btn-sm" aria-label="Center Align"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span></button>
				</div>
				<!-- <input type="submit" class="btn btn-default" name="submit" id="add" value="simpan"> -->
			</td>