<?php
session_start();
require_once '../../config/database.php';

// ตรวจสอบสิทธิ์ - อนุญาตทั้ง Admin และ Student
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'student'])) {
    die("Unauthorized Access");
}

if (!isset($_POST['request_ids']) || count($_POST['request_ids']) == 0) {
    die("กรุณาเลือกนิสิตอย่างน้อย 1 คน");
}

$request_ids = $_POST['request_ids']; // Array of request IDs

try {
    // 1. ดึงข้อมูลบริษัทและรอบการฝึกงาน (จาก request แรก)
    $first_id = $request_ids[0];
    $stmt = $pdo->prepare("
        SELECT 
            c.name as c_name,
            c.address as c_address,
            c.phone as c_phone,
            ir.round_id,
            COALESCE(r.round_name, 'ไม่ระบุรอบ') as round_name,
            COALESCE(r.start_date, ir.start_date) as start_date,
            COALESCE(r.end_date, ir.end_date) as end_date
        FROM internship_requests ir
        LEFT JOIN companies c ON ir.company_id = c.id
        LEFT JOIN internship_rounds r ON ir.round_id = r.id
        WHERE ir.id = ?
    ");
    $stmt->execute([$first_id]);
    $company_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company_data || !$company_data['c_name']) {
        die("ไม่พบข้อมูลบริษัท");
    }

    // จัดรูปแบบวันที่
    function formatDateThai($dateStr) {
        if (!$dateStr) return "-";
        $thai_months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
            7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $date = strtotime($dateStr);
        $d = date('j', $date);
        $m = $thai_months[date('n', $date)];
        $y = date('Y', $date) + 543;
        return "$d $m $y";
    }

    $period_text = formatDateThai($company_data['start_date']) . " ถึง " . formatDateThai($company_data['end_date']);

    // 2. ดึงข้อมูลนิสิตทั้งหมดที่เลือก
    $placeholders = str_repeat('?,', count($request_ids) - 1) . '?';
    $stmt_students = $pdo->prepare("
        SELECT 
            COALESCE(u.full_name, 'ไม่ระบุ') as std_name,
            COALESCE(s.student_id, ir.student_id) as std_code,
            COALESCE(u.phone, '') as std_phone,
            'ไม่ระบุ' as major_name
        FROM internship_requests ir
        LEFT JOIN students s ON ir.student_id = s.user_id
        LEFT JOIN users u ON s.user_id = u.id
        WHERE ir.id IN ($placeholders)
        ORDER BY s.student_id ASC
    ");
    $stmt_students->execute($request_ids);
    $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>หนังสือขอความอนุเคราะห์ให้นิสิตฝึกประสบการณ์วิชาชีพ</title>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style type="text/css">
    body {
        font-family: 'Sarabun', 'TH Sarabun New', sans-serif;
        font-size: 12px;
        line-height: 1.5;
        margin: 20px;
        max-width: 21cm;
    }
    .thsarabunnew {
        font-family: 'Sarabun', 'TH Sarabun New', sans-serif;
    }
    table {
        font-size: 12px;
    }
    @media print {
        body {
            margin: 0;
            padding: 15mm;
        }
        button {
            display: none !important;
        }
        input, textarea {
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }
        .section { 
            height: 100%;
            margin: 0px;
            page-break-after: always;
        } 
    }
    /* Utility classes for display */
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 4px;
        text-decoration: none;
    }
    .btn-success {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;
    }
    .btn-secondary {
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }
</style>
<script type="text/javascript">
    function print_page() {
        window.print();
    }
</script>
</head>

<body>
<div align="right" style="margin-bottom: 20px;">
    <button type="button" class="btn btn-secondary" style="width:110px;height:32px;" onClick="window.close();"> 
       ปิดหน้าต่าง
    </button>
    <button type="button" class="btn btn-success" style="width:180px;height:32px;" onClick="print_page();"> 
      พิมพ์
    </button>
</div>

<?php
	// ถ้ามีนิสิตมากกว่า 1 คน ให้แบ่งหน้าการพิมพ์ (หน้าแรกเป็นหนังสือราชการ)
	if (count($students) > 1) {	
		echo "<div class = 'section'>"; 
	}
?>

<!-- ส่วนหัวหนังสือราชการ -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="thsarabunnew" style="font-size:12px; line-height: 1.5;">
  <tr>
    <td colspan="3" align="center">
        <!-- ใช้รูปครุฑ Placeholder หรือ path รูปจริงของคุณ -->
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/Thai_Government_Garuda_Emblem_%28Version_2%29.svg/1200px-Thai_Government_Garuda_Emblem_%28Version_2%29.svg.png" width="60">
    </td>
  </tr>
  <tr>
    <td width="30%" valign="top">ที่ ศธ 0530.10/</td>
    <td width="40%">&nbsp;</td>
    <td width="30%">คณะการบัญชีและการจัดการ<br>
    มหาวิทยาลัยมหาสารคาม<br>
    ตำบลขามเรียง อำเภอกันทรวิชัย<br>
    จังหวัดมหาสารคาม 44150</td>
  </tr>
  <tr>
    <td valign="top">&nbsp;</td>
    <td align="center"><br>
        <?php 
        $thai_months = [1=>'มกราคม',2=>'กุมภาพันธ์',3=>'มีนาคม',4=>'เมษายน',5=>'พฤษภาคม',6=>'มิถุนายน',7=>'กรกฎาคม',8=>'สิงหาคม',9=>'กันยายน',10=>'ตุลาคม',11=>'พฤศจิกายน',12=>'ธันวาคม'];
        echo date('j') . " " . $thai_months[date('n')] . " " . (date('Y')+543);
        ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="12" colspan="3"></td>
  </tr>
</table>

<!-- ส่วนเนื้อหาหนังสือ -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="thsarabunnew" style="font-size:12px; line-height: 1.5;">
  <tr>
    <td width="10%" valign="top">เรื่อง</td>
    <td colspan="2">ขอความอนุเคราะห์ให้นิสิตฝึกประสบการณ์วิชาชีพ</td>
  </tr>
  <tr>
    <td height="10" colspan="3"></td>
  </tr>
  <tr>
    <td valign="top">เรียน</td>
    <td colspan="2">
        ผู้จัดการ / ผู้อำนวยการ / หัวหน้าฝ่ายบุคคล<br>
        <?= htmlspecialchars($company_data['c_name']) ?>
    </td>
  </tr>
  <tr>
    <td height="10" colspan="3"></td>
  </tr>
  <tr>
    <td colspan="2" valign="top">สิ่งที่ส่งมาด้วย</td>
    <td width="85%">
        1. แบบตอบรับนิสิตเข้ารับการฝึกประสบการณ์วิชาชีพ<br>
        2. ประวัติย่อ (Resume)
    </td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="thsarabunnew" style="font-size:12px; line-height: 1.5; margin-top: 15px;">
  <tr>
    <td style="text-indent: 2.5cm; text-align: justify;">
        ด้วยคณะการบัญชีและการจัดการ ได้เปิดการเรียนการสอนนิสิตระดับปริญญาตรี หลักสูตรบัญชีบัณฑิต และหลักสูตรบริหารธุรกิจบัณฑิต จำนวน 9 สาขาวิชา ได้แก่ การตลาด การจัดการ คอมพิวเตอร์ธุรกิจ ธุรกิจระหว่างประเทศ (หลักสูตรนานาชาติ) การจัดการทรัพยากรมนุษย์ การจัดการการประกอบการ การบริหารการเงิน เทคโนโลยีสารสนเทศธุรกิจ และการจัดการพาณิชย์อิเล็กทรอนิกส์ และหลักสูตรเศรษฐศาสตรบัณฑิต สาขาวิชาเศรษฐศาสตร์ธุรกิจ และคณะฯ พิจารณาแล้วเห็นว่าหน่วยงานของท่านมีความเหมาะสมอย่างยิ่งในการเพิ่มทักษะและเสริมสร้างประสบการณ์ในการทำงานให้แก่นิสิต
    </td>
  </tr>
  <tr>
    <td style="text-indent: 2.5cm; text-align: justify;">
        ดังนั้น จึงใคร่ขอความอนุเคราะห์ให้
        <?php
        if (count($students) > 1) {
            echo "นิสิตคณะการบัญชีและการจัดการ จำนวน " . count($students) . " คน (รายชื่อดังแนบ)";
        } else {
            $student = $students[0];
            echo "<strong>" . htmlspecialchars($student['std_name']) . "</strong> นิสิตสาขาวิชา" . htmlspecialchars($student['major_name']);
        }
        ?>
        เข้ารับการฝึกประสบการณ์วิชาชีพในหน่วยงานของท่าน <strong>ระหว่าง <?= $period_text ?></strong>
    </td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td style="text-indent: 2.5cm; text-align: justify;">
        จึงเรียนมาเพื่อโปรดพิจารณาให้ความอนุเคราะห์ และขอบพระคุณในความกรุณาของท่านมา ณ โอกาสนี้
    </td>
  </tr>
</table>

<!-- ส่วนลงนาม -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="thsarabunnew" style="font-size:12px; margin-top: 40px;">
  <tr>
    <td width="50%"></td>
    <td align="center">
        ขอแสดงความนับถือ
        <br><br><br><br>
        (ผู้ช่วยศาสตราจารย์ ดร.นิติพงษ์ ส่งศรีโรจน์)<br>
        คณบดีคณะการบัญชีและการจัดการ<br>
        มหาวิทยาลัยมหาสารคาม
    </td>
  </tr>
</table>

<!-- ส่วนท้าย -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="thsarabunnew" style="font-size:12px; margin-top: 50px;">
  <tr>
    <td>
        ฝ่ายกิจการนิสิต<br>
        คณะการบัญชีและการจัดการ<br>
        มหาวิทยาลัยมหาสารคาม<br>
        โทรศัพท์ 0-4375-4333 ต่อ 3433<br>
        โทรสาร 0-4375-4422
    </td>
  </tr>
</table>

<?php
	// ปิด section หน้าแรก
	if (count($students) > 1) {	
		echo "</div>"; 
	}
?>

<!-- ส่วนหน้ารายชื่อ (ถ้ามีมากกว่า 1 คน) -->
<?php if (count($students) > 1): ?>
    <br>
    <div class="section">
        <div align="center" style="font-size:16pt; font-weight:bold; margin-bottom: 20px;">
            รายชื่อนิสิตคณะการบัญชีและการจัดการ<br>
            ขอความอนุเคราะห์เข้าฝึกประสบการณ์วิชาชีพ ณ <?= htmlspecialchars($company_data['c_name']) ?><br>
            ระหว่าง <?= $period_text ?>
        </div>

        <table width="100%" border="1" cellspacing="0" cellpadding="5" class="thsarabunnew" style="font-size:16pt; border-collapse:collapse;">
            <thead>
                <tr>
                    <th width="10%">ที่</th>
                    <th width="20%">รหัสนิสิต</th>
                    <th width="30%">ชื่อ-สกุล</th>
                    <th width="25%">สาขาวิชา</th>
                    <th width="15%">เบอร์โทร</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $std): ?>
                <tr>
                    <td align="center"><?= $index + 1 ?></td>
                    <td align="center"><?= htmlspecialchars($std['std_code']) ?></td>
                    <td><?= htmlspecialchars($std['std_name']) ?></td>
                    <td><?= htmlspecialchars($std['major_name']) ?></td>
                    <td align="center"><?= htmlspecialchars($std['std_phone']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

</body>
</html>
