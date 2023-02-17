
const form = document.querySelector('.typing-area');
const inputField = form.querySelector('input');
const sendBtn = form.querySelector('button');
const chatBox = document.querySelector('.chat-box');

form.onclick = (e)=>{
  e.preventDefault();
}

sendBtn.onclick = (e)=>{

  if(inputField.value.trim() === ''){
    return;
  }

  const postData = {
    inputField: inputField.value
  }

  const options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(postData)
  }

  fetch('php/insert-chat.php', options)
    .then(response => response.text())
    .then(data => {
      inputField.value = '';
      scrollToBottom();
    });

}

chatBox.onmouseenter = ()=>{
  chatBox.classList.add('active');
}

chatBox.onmouseleave = ()=>{
  chatBox.classList.remove('active');
}

function scrollToBottom(){
  
  chatBox.scrollTop = chatBox.scrollHeight;
}

let prevData = '';
setInterval(()=>{
  fetch('php/get-chat.php')
    .then(res => res.text())
    .then(data => {
      if(prevData === data){
        return;
      }
      if(!chatBox.classList.contains('active'))
        scrollToBottom()
        
      chatBox.innerHTML = data;
      prevData = data;
      scrollToBottom();
    });

}, 500);