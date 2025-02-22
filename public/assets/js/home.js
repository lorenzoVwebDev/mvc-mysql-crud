//services
import { insertTask, selectTask, deleteTask, updateTask } from './services/dashboard/appointmentCrud.js'
//views
import { renderNewTask, renderStoredTask, renderEditModal } from './view/dashboard/appointmentCrud.view.js';
//global variables
import { url } from './utils/globalVariables.js'


const deleteClick = async (event) => {
  const id = event.target.parentElement.dataset.id;
  deleteTask(url, id);
  const responseObject = await selectTask(url, 'ALL');
  const {result, response} = responseObject;
  renderStoredTask(result, response);
  addDelete();
  addEdit();
}

const updateClick = async (event) => {
  const id = event.target.parentElement.dataset.id;
  const modal = renderEditModal();
  document.getElementById('update-appointment-list').addEventListener('submit', async (newEvent) => {
    newEvent.preventDefault();
    const form = new FormData(newEvent.target);
    form.append('id', id);
    const all = await updateTask(url, form);
    modal.style.display = 'none';
    const responseObject = await selectTask(url, all.result);
    const {result, response} = responseObject;
    renderStoredTask(result, response);
    addDelete();
    addEdit()
  })
}


document.getElementById('appointment-list').addEventListener('submit', async (event) => {
  try {
    event.preventDefault();
    const form = event.target;
    const responseObject = await insertTask(form, url);
    const { result, response } = responseObject;
    renderNewTask(result, response);
    addDelete();
    addEdit();
  } catch (err) {
    console.error(err);
  }
});

document.getElementById('read-task').addEventListener('click', async (event) => {
  try {
  const data = event.target.dataset;
  const arrayId = data.id
  const responseObject = await selectTask(url, arrayId);
  const {result, response} = responseObject;
  renderStoredTask(result, response);
  addDelete();
  addEdit()
  } catch (err) {
    console.error(err)
  }
})

function addDelete() {
    document.querySelectorAll('.delete-task').forEach((element) => {
    element.removeEventListener('click', deleteClick)
    element.addEventListener('click', deleteClick)
  })
}

function addEdit() {
  document.querySelectorAll('.update-task').forEach((element) => {
    element.removeEventListener('click', updateClick)
    element.addEventListener('click', updateClick)
  })
}



























