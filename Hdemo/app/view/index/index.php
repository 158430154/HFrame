<div id="login_box">
    <span id="sys_title"><?php echo H::app()->getConfig('app_name'); ?></span>
    <div>
        <input id="user_account" value="<?php echo isset($cookie['account'])?$cookie['account']:''; ?>" class="login_input" placeholder="账号" type="text">
    </div>
    <div>
        <input id="user_pwd" value="" class="login_input" placeholder="密码" type="password">
    </div>
    <div id="err_msg"></div>
    <a id="btn_login" href="javascript:;">登录</a>
    <div id="copyright">© 2016</div>

    <div class="upload_demo" style="margin-top: 50px;">
        <form action="<?php echo $this->genurl('Upload/file'); ?>" method="post" enctype="multipart/form-data">

            文件上传DEMO
            <input type="file" name="file_key">
            <input type="hidden" name="file_key" value="file_key">

            <button type="submit">点击上传</button>
        </form>
    </div>
</div>