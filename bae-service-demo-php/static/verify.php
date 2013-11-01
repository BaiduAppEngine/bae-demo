<?php
	require_once "../image/generateVCode.php";
?>
<HTML>
  <HEAD>
    <TITLE>Hello World</TITLE>
    <META http-equiv="Pragma" content="no-cache">
    <META http-equiv="Cache-Control" content="no-cache,no-store">
    <META http-equiv="Content-Type" content="text/html;charset=utf-8"/>
  </HEAD>
<BODY>
  <table border="1" height="100" width="80%">
    <caption><h3>验证码服务</h3></caption>
  	<form method="POST" action="/image/verifyVCode.php">
      <tr>
      <td>
      input：<input type="text" name="input"/>
      </td>
      <td>
		<?php
			$memcache = new BaeMemcache($cacheid, $chost.':'.$cport, $cuser, $cpwd);
			$imgurl = $memcache->get("imgurl");
			echo "<img src=$imgurl />";
			$memcache->delete("imgurl");
		?>
      </td>
      </tr>
      	<?php
			$memcache = new BaeMemcache($cacheid, $chost.':'.$cport, $cuser, $cpwd);
			$secret = $memcache->get("secret");
			echo "<input type=hidden name=secret value={$secret} />";
			$memcache->delete("secret");
	  	?>
      
      <tr align= "center">
        <td>
      <input type="submit" align="center"/>
  		</td>
      </tr>
    </form>
  </table>

</BODY>
</HTML>
