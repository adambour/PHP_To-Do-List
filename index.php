<?php
global $conn;
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST['task_name'];
    if (!empty($task_name)) {
        $stmt = $conn->prepare("INSERT INTO tasks (task_name, closed_tasks, created_at) VALUES (?, 0, CURDATE())");
        $stmt->bind_param("s", $task_name);
        $stmt->execute();
        $stmt->close();
    }
}


$open_tasks = $conn->query("SELECT * FROM tasks WHERE closed_tasks = 0");
$closed_tasks = $conn->query("SELECT * FROM tasks WHERE closed_tasks = 1");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">To Do List</h1>
    <form class="mb-4" action="index.php" method="post">
        <div class="input-group">
            <input type="text" name="task_name" class="form-control" placeholder="New Task"/>
            <button type="submit" class="btn btn-primary">ADD</button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h2 class="text-center">Open Tasks</h2>
                <ul class="list-group">
                    <?php if ($open_tasks->num_rows > 0): ?>
                        <?php while ($task = $open_tasks->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $task['task_name']; ?>
                                <div>
                                    <a href="complete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-success">Complete Task</a>
                                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-danger">Delete Task</a>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">No Open Task Found</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-6">
                <h2 class="text-center">Closed Tasks</h2>
                <ul class="list-group">
                    <?php if ($closed_tasks->num_rows > 0): ?>
                        <?php while ($task = $closed_tasks->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $task['task_name']; ?>
                                <div>
                                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-danger">Delete Task</a>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="list-group-item">No Closed Task Found</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
