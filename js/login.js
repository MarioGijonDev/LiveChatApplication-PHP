
'user strict';

const form = document.querySelector('.signup form');
const continueBtn = form.querySelector('.button input');

const errorText = form.querySelector('.error-txt');

form.onsubmit = (e)=>{

  e.preventDefault();

}

continueBtn.onclick = (e)=>{

  let formData = new FormData(form)

  // Fetch Method
  let options = {
    method: 'post',
    body: formData
  }

  fetch('php/login.php', options)
    .then(res => res.text())
    .then(data => {

      console.log(data);

      if(data == 'success'){

        location.href = 'users.php'; 

      }else{

        

        errorText.textContent = data;

        errorText.style.display = 'block';

      }

    });

};