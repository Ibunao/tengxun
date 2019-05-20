<h1>智思云打卡</h1>

<form class="form-inline">
    <div class="row">
	    <div class="col-lg-6">
		    <div class="input-group">
		    <div class="input-group-addon">会员</div>
		    <select id="userId" class="form-control">
		    	<?php foreach ($users as $name => $userId): ?>
		    		<option value="<?=$userId ;?>"><?=$name ;?></option>
		    	<?php endforeach ?>
			</select>
		    <span class="input-group-btn">
		        <button class="btn btn-default" type="button" id="login">打卡</button>
		    </span>
		    </div><!-- /input-group -->
	    </div><!-- /.col-lg-6 -->
	</div><!-- /.row -->
</form>
<script>
<?php $this->beginBlock('pageEndJs') ?>
	$("#login").click(function(){
	  $.post("/zhisiyun/login1",
	  {
	    userId: $('#userId').val(),
	  },
	  function(data, status){
	    alert("Data: " + data + "\nStatus: " + status);
	  });
	});
<?php $this->endBlock() ?>
</script>
<!-- //将编写的js代码注册到页面底部 -->
<?php $this->registerJs($this->blocks['pageEndJs'],\yii\web\View::POS_LOAD); ?> 