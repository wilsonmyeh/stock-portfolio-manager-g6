<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<script>
function startTime() {
	var today = new Date();
	var date = today.toDateString();
	var hour = (today.getHours()+11)%12 + 1;
	var min = today.getMinutes();
	var sec = today.getSeconds();
	hour = checkTime(hour);
	min = checkTime(min);
	sec = checkTime(sec);
	document.getElementById('clock').innerHTML = date + "\n" + hour + ":" + min + ":" + sec;
	var t = setTimeout(startTime, 500);
}
function checkTime(i) {
	if (i < 10) {
		i = "0" + i;
	}
	return i;
}
</script>
</head>
<body onload="startTime()" style="text-align:center; color:white; font-size:128pt;" background="helloworld_files/small_waves_1920x1200.jpg">
<div id="clock">Sun Feb 21 2016
05:49:08</div>

</body></html>