<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/dbConnection.php';

$conn = db();

/* -------------------------------
   BASIC CSRF PROTECTION
---------------------------------*/
function csrf_valid($token) {
    return isset($_SESSION['csrf_token']) &&
           hash_equals($_SESSION['csrf_token'], $token);
}

/* If invalid request or no POST, block immediately */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !csrf_valid($_POST['csrf_token'] ?? '')) {

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Request',
            text: 'Your session expired or the request is invalid.',
        }).then(() => {
            window.location.href = '../public/login.php';
        });
    </script>";
    exit;
}

/* ---------------------------------
   GET ACTION
----------------------------------*/
$action = $_POST['action'] ?? '';
$user_id = intval($_POST['user_id'] ?? 0);

/* ---------------------------------
   ACTION HANDLERS
----------------------------------*/

switch ($action) {


/* ======================================================
   1. APPROVE USER
========================================================*/
case 'approve_user':

    $stmt = $conn->prepare("UPDATE users SET account_status='Approved' WHERE user_id=?");
    $stmt->execute([$user_id]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'User Approved!',
            text: 'The user has been approved successfully.'
        }).then(() => {
            window.location.href = '../admin/userManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   2. REJECT USER
========================================================*/
case 'reject_user':

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=? AND account_status='For Approval'");
    $stmt->execute([$user_id]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'User Rejected',
            text: 'User has been removed.'
        }).then(() => {
            window.location.href = '../admin/userManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   3. ADD EQUIPMENT
========================================================*/
case 'add_equipment':

    $name = trim($_POST['equipment_name'] ?? '');
    $qty  = intval($_POST['quantity'] ?? 0);

    $stmt = $conn->prepare("INSERT INTO equipment (equipment_name, quantity) VALUES (?, ?)");
    $stmt->execute([$name, $qty]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Equipment Added',
        }).then(() => {
            window.location.href = '../admin/equipmentManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   4. UPDATE EQUIPMENT
========================================================*/
case 'update_equipment':

    $eid  = intval($_POST['equipment_id'] ?? 0);
    $name = trim($_POST['equipment_name'] ?? '');
    $qty  = intval($_POST['quantity'] ?? 0);

    $stmt = $conn->prepare("UPDATE equipment SET equipment_name=?, quantity=? WHERE equipment_id=?");
    $stmt->execute([$name, $qty, $eid]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Updated Successfully',
        }).then(() => {
            window.location.href = '../admin/equipmentManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   5. DELETE EQUIPMENT
========================================================*/
case 'delete_equipment':

    $eid = intval($_POST['equipment_id'] ?? 0);

    $stmt = $conn->prepare("DELETE FROM equipment WHERE equipment_id=?");
    $stmt->execute([$eid]);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Equipment Deleted',
        }).then(() => {
            window.location.href = '../admin/equipmentManagement.php';
        });
    </script>";
    exit;



/* ======================================================
   DEFAULT
========================================================*/
default:

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Unknown Action',
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>";
    exit;
}
