import "https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js";

let modal = `
<div class="modal modal-sheet position-static d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
    <div class="modal-dialog" role="document">
      <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-button"></button>
        </div>
  
        <div class="modal-body p-5 pt-0">
            <form id="update-appointment-list">
                <fieldset>
                    <div class="form-floating mb-3">
                      <input class="form-control rounded-3" type="text" name="title" id="title" form="update-appointment-list" value="appointment" required/>
                      <label for="title">Title:</label>
                    </div>
                    <div class="mb-3">
                      <label for="description">Description:</label>
                      <textarea name="description" id="description" form="update-appointment-list"></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="duedate">Due Date:</label>
                      <input type="date" name="duedate" id="duedate" form="update-appointment-list" min="${dayjs().format('YYYY-MM-DD')}" value="${dayjs().format('YYYY-MM-DD')}" required/>
                    </div>
                    <div class="mb-3">
                      <label for="priority">Priority:</label>
                      <select name="priority" id="priority" form="update-appointment-list">
                          <option value="low">Low</option>
                          <option value="medium">Medium</option>
                          <option value="high">High</option>
                      </select>
                    </div>
                    <div class="form-actions form-floating mb-3">
                    <input type="hidden" name="task" id="task" value="task"/>
                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit" id="submit-button">Update</button>
                    </div>
                </fieldset>
            </form>
        </div>
      </div>
    </div>
  </div>
`

export default modal;