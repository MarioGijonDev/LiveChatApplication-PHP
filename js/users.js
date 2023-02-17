
const searchBar = document.querySelector('.users .search input');
const searchBtn = document.querySelector('.users .search button');
const userList = document.querySelector('.users .users-list');

searchBtn.onclick = () => {
  searchBar.classList.toggle('active');
  searchBar.focus();
  searchBtn.classList.toggle('active');
  searchBar.value = '';
};

searchBar.onkeyup = ()=>{

  $data = {searchValue: searchBar.value};

  // Fetch Method
  let options = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify($data)
    
  }

  fetch('php/search.php', options)
    .then(res => res.text())
    .then(data => {userList.innerHTML = data; console.log("Hola")});
}

setInterval(()=>{

  fetch('php/users.php')
    .then(res => res.text())
    .then(data => {
      if(!searchBar.classList.contains('active'))
        userList.innerHTML = data
    });

}, 500);