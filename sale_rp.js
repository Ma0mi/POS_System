function setMonth() {
  var today = new Date();
  var year = today.getFullYear();
  var month = today.getMonth() + 1; // เดือนนับจาก 0
  if (month < 10) {
    month = '0' + month; // เพิ่ม 0 นำหน้าเลขเดือนถ้าไม่เต็ม 2 ตำแหน่ง
  }
  var lastDay = new Date(year, month, 0).getDate(); // วันสุดท้ายของเดือน
  var startDate = year + '-' + month + '-01'; // เริ่มต้นที่วันแรกของเดือน
  var endDate = year + '-' + month + '-' + lastDay; // สิ้นสุดที่วันสุดท้ายของเดือน
  document.getElementById('start_date').value = startDate;
  document.getElementById('end_date').value = endDate;
}


  function setToday() {
    var today = new Date();
    var year = today.getFullYear();
    var month = today.getMonth() + 1; // เดือนนับจาก 0
    var day = today.getDate();
    if (month < 10) {
      month = '0' + month; // เพิ่ม 0 นำหน้าเลขเดือนถ้าไม่เต็ม 2 ตำแหน่ง
    }
    if (day < 10) {
      day = '0' + day; // เพิ่ม 0 นำหน้าเลขวันถ้าไม่เต็ม 2 ตำแหน่ง
    }
    var todayDate = year + '-' + month + '-' + day;
    document.getElementById('start_date').value = todayDate;
    document.getElementById('end_date').value = todayDate;
  }