<link rel="stylesheet" href="/pay/main.css">
<script type="text/javascript" src="/scripts/psi.js"></script>
<div class="pm">
	<b style="float:left;">Коммерский отдел предлагает следующие услуги:</b>
    <span style="float:right"><? if($u->info['id']>0){ echo $u->microLogin($u->info['id'],1); } ?></span>
</div>
<div class="pm2">
	<center>
    	Если вы не нашли подходящий раздел или услугу, вы можете обратиться к Администрации через e-mail:<br />
    	<a href="mailto:support@xcombats.com">support@xcombats.com</a>, в теме письма напишите "Коммерческий отдел".
    </center>
    <div>
    	<hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock1" value="1">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Покупка Еврокредитов онлайн');</script></span>
    	
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock10" value="10">
		<script>psi.radioPring('cmmc','cmmc',true,null,'VIP Клуб: Доступ к бесплатному использованию некоторых услуг и свитков');</script></span>
        
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock11" value="11">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Покупка личных Артефактов');</script></span>
        
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock2" value="2">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Уникальные образы, смайлики и подарки');</script></span> 
    	
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock3" value="3">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Клановые образы, смайлики и подарки');</script></span>
    	    	
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock12" value="12">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Покупка рун и чарок');</script></span>
        
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock5" value="5">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Обмен рун, чарок и заточек');</script></span>
        
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock4" value="4">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Обмен артефактов и предметов');</script></span>
    	
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock9" value="9">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Реликты: Персональные реликты и клановые');</script></span>
        
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock7" value="7">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Персональные услуги: Покупка склонности, смена логина, пола, пароля и т.д.');</script></span>
                
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock6" value="6">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Клановые услуги: Смена названия, значка, склонности и т.д.');</script></span>
        
        <hr class="hr0" />
        <span class="cp radio1txt" id="cmmcblock8" value="8">
		<script>psi.radioPring('cmmc','cmmc',true,null,'Услуги модерации: Оплата игровых штрафов и пошлин');</script></span>
    </div>
</div>