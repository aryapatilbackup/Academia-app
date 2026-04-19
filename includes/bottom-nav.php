<?php
if (!isset($conn)) {
    include 'config/db.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
$student_id = $_SESSION['user_id'];

$countResult = $conn->query("
  SELECT COUNT(*) AS unread_count
  FROM notices n
  LEFT JOIN notice_reads nr
    ON n.id = nr.notice_id
    AND nr.student_id = '$student_id'
  WHERE (n.target='student' OR n.target='all')
  AND nr.id IS NULL
");

$unread = 0;
if ($countResult) {
  $unread = $countResult->fetch_assoc()['unread_count'];
}
?>
<?php $page = basename($_SERVER['PHP_SELF']); ?>

<nav class="bottom-nav">
  <a href="student-dashboard.php" class="<?= $page=='student-dashboard.php'?'active':'' ?>">
    🏠 <span>Home</span>
  </a>

  <a href="student-chatbox.php" class="<?= $page=='student-chatbox.php'?'active':'' ?>">
    💬 <span>Chatbox</span>
  </a>

  <a href="student-id.php" class="<?= $page=='student-id.php'?'active':'' ?>">
    🪪 <span>ID Card</span>
  </a>

  <a href="student-notice.php" 
   class="nav-item <?= $page=='student-notice.php'?'active':'' ?>">

    <div class="nav-icon-wrapper">
        <span class="nav-icon">📢</span>

        <?php if ($unread > 0): ?>
            <span class="nav-badge"><?= $unread ?></span>
        <?php endif; ?>
    </div>

    <span class="nav-label">Notice</span>
</a>
  <a href="student-profile.php" class="<?= $page=='student-profile.php'?'active':'' ?>">
    👤 <span>Profile</span>
  </a>
</nav>

<script>
window.onload=function(){

const toast=document.getElementById("toast");

if(toast){

toast.classList.add("show");

setTimeout(()=>{
toast.classList.remove("show");
},3000);

if(window.history.replaceState){
const cleanURL = window.location.pathname;
window.history.replaceState({}, document.title, cleanURL);
}

}

}
</script>