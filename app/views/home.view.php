<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MVC-mysql-crud</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/headers/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <link href="<?=ROOT?>public/assets/bootstrap.assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?=ROOT?>public/assets/css/home.css"/>
  <link rel="stylesheet" href="<?=ROOT?>public/assets/css/common.css"/>
</head>
<body>
  <section class="git-header-section"></section>
  <section class="main-header">
  </section>

  <section class="main-section">

    <section class="crud-section">
      <h3>CRUD operations on Logs</h3>
      <form id="appointment-list">
        <fieldset>
          <label for="title">Title:</label>
          <input type="text" name="title" id="title" form="appointment-list" value="appointment" min="0" max="25" required/>
          <label for="description">Description:</label>
          <textarea name="description" id="description" form="appointment-list" min="0" max="100"></textarea>
          <label for="duedate">Due Date:</label>
          <input type="date" name="duedate" id="duedate" form="appointment-list" min="<?=date('Y-m-d')?>" value="<?=date('Y-m-d')?>"/>
          <label for="priority">Priority:</label>
          <select name="priority" id="priority" form="appointment-list">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
          <div class="form-actions">
            <div>
              <input type="hidden" name="task" id="task" value="task"/>
              <button type="submit" id="submit-button">Add/Update</button>
              <button type="clear">Clear</button>
            </div>
          </div>
        </fieldset>
      </form>
      <button id="read-task" data-id="ALL">Open Task List</button>
      <div id="tasklist" class="tasklist"></div>
    </section>

    <div id="task-list">
    </div>
  </section>

  <section class="footer-section"></section>
  <div class="modal-container">
  </div>
  <div class="edit-modal-container">
  </div>

  <script src="<?= ROOT?>public/assets/bootstrap.assets/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= ROOT?>public/assets/js/common-components/index.js" type="module"></script>
  <script src="<?= ROOT?>public/assets/js/home.js" type="module"></script>
  <script>
  </script>
</body>
</html>