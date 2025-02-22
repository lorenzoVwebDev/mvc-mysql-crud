export async function insertTask(form, url) {
  const formData = new FormData(form);
  
  const response = await fetch(`${url}/admin/taskcrud/insert`, {
    method: 'POST',
    body: formData
  });

  if (response.status >= 200 && response.status < 400) {
    const result = await response.json()
    return {
      result,
      response
    }
  } else if (response.status >= 400 && response.status < 500) {
    const result = await response.json();
    return {
      result, response
    }
  }
}

export async function selectTask(url, id) {
  console.log(id)
  const response = await fetch(`${url}/admin/taskcrud/select?id=${id}`);
  
  if (response.status >= 200 && response.status < 400) {
    const result = await response.json()
    return {
      result,
      response
    }
  } else if (response.status >= 400 && response.status < 500) {
    const result = await response.json();
    return {
      result, response
    }
  }
}


export async function deleteTask(url, id) {
  console.log(url, id)
  const response = await fetch(`${url}/admin/taskcrud/delete/${id}`, {
    method: 'DELETE'
  });
  
  if (response.status >= 200 && response.status < 400) {
    const result = await response.text()
    return {
      result,
      response
    }
  } else if (response.status >= 400 && response.status < 500) {
    const result = await response.text();
    return {
      result, 
      response
    }
  }
}

export async function updateTask(url, form) {
  let formObject = {};
  form.forEach((input, key) => {
    formObject[key] = input
  })

  const response = await fetch(`${url}/admin/taskcrud/update`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(formObject)
  });

  if (response.status >= 200 && response.status < 400) {
    const result = await response.text()
    return {
      result,
      response
    }
  } else if (response.status >= 400 && response.status < 500) {
    const result = await response.json();
    return {
      result, response
    }
  }
}