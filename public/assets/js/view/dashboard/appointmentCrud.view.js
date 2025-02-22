
export function renderNewTask(result, response) {
  if (response.status >= 200 && response.status<400) {
    const {title, description, duedate, priority, index} = result;
    const taskContainer = document.getElementById('tasklist');
    const taskDiv = document.createElement('div');
    taskDiv.classList.add('task');
  
    taskDiv.innerHTML = `
      <div class="task-info">
        <strong>${title}</strong>
        <p>${description}</p>
        <small>Due: ${duedate}</small>
        <small>Priority: ${priority}</small>
      </div>
      <div class="task-actions" data-id=${index}>
        <button class="update-task">Edit</button>
        <button class="delete-task">Delete</button>
      </div>  
    `
  
    taskContainer.append(taskDiv);
  } else if (response.status >= 400 && response.status < 500) {
    var modal = document.querySelector('.modal-container');
    modal.style.display = 'block';
    var modalTitle = document.getElementById('modal-title');
    var modalText = document.getElementById('modal-text');
    modalTitle.innerText = `Error ${result.status.toString()}`;
    modalText.innerHTML = `${result.message}`;
  } else if (response.status >= 500) {
    var modal = document.querySelector('.modal-container');
    modal.style.display = 'block';
    var modalTitle = document.getElementById('modal-title');
    var modalText = document.getElementById('modal-text');
    modalTitle.innerText = `Error ${result.status.toString()}`;
    modalText.innerHTML = `${result.message}`;
  }
}

export function renderStoredTask(result, response) {
  if (response.status >= 200 && response.status<400) {
    const taskContainer = document.getElementById('tasklist');
    taskContainer.innerHTML = '';
    const resultArray = Object.entries(result);
    console.log(resultArray);
    resultArray.forEach((element)=> {
        const task = `
        <div class="task-info">
          <strong>${element[1].tasktitle}</strong>
          <p>${element[1].taskdescription}</p>
          <small>Due: ${element[1].taskduedate}</small>
          <small>Priority: ${element[1].taskpriority}</small>
        </div>
        <div class="task-actions" data-id=${element[0]}>
          <button class="update-task">Edit</button>
          <button class="delete-task">Delete</button>
        </div>  
      `
        taskContainer.innerHTML += task;
    })
  } else if (response.status >= 400 && response.status < 500) {
    var modal = document.querySelector('.modal-container');
    modal.style.display = 'block';
    var modalTitle = document.getElementById('modal-title');
    var modalText = document.getElementById('modal-text');
    modalTitle.innerText = `Error ${result.status.toString()}`;
    modalText.innerHTML = `${result.message}`;
  } else if (response.status >= 500) {
    var modal = document.querySelector('.modal-container');
    modal.style.display = 'block';
    var modalTitle = document.getElementById('modal-title');
    var modalText = document.getElementById('modal-text');
    modalTitle.innerText = `Error ${result.status.toString()}`;
    modalText.innerHTML = `${result.message}`;
  }
}

export function renderEditModal() {
  var modal = document.querySelector('.edit-modal-container');
  modal.style.display = 'block';
  document.getElementById('close-button').addEventListener('click', () => {
    modal.style.display = 'none';
  })
  return modal;
}





