<?php
require_once __DIR__ . '../../../middleware/middleware-user.php';
@require_once __DIR__ . '/../../validations/session.php';
require_once __DIR__ . '/../../repositories/expenseCalendarRepository.php';

$userID = user();
$expenses = getExpensesToCalendar($userID['userID']);

$events = [];
foreach ($expenses as $expense) {
    $events[] = [
        'expenseID' => $expense['expenseID'],
        'expenseDescription' => $expense['expenseDescription'],
        'paymentDate' => $expense['paymentDate'],
    ];
}
?>

    <meta charset="UTF-8">
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
    </style>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">User Dashboard</li>
            <li class="breadcrumb-item">Expenses Calendar</li>
        </ol>
    </nav>
    <div id='calendar'></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentDate = new Date();
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: currentDate.toISOString().slice(0, 10),
            editable: false,
            selectable: true,
            businessHours: true,
            dayMaxEvents: true,
            events: <?php echo json_encode($events); ?>
        });
        calendar.render();
    });
</script>