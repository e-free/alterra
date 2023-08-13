<!DOCTYPE html>
<html lang="ru">
	<title>Тестовое задание</title>	
	<header>
		<meta charset="utf-8">
		
		<style>
			@import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
			body{
			font-family: 'Open Sans', sans-serif;
			background-color: #eceff4;
			
			}
			.container{
			max-width: 320px;
			margin:100px auto 0 auto;
			
			}
			.wrap {
			padding:22px 25px 25px 25px;
			
			}
			.form, .form_head, .contacts{
			
			background-color: #ffffff;
			}
			.form, .form input, .form button, .contacts{
			border-radius: 5px;
			}
			
			.input_wrap {
			position: relative;
			text-align: center;
			margin-bottom:10px;
			}
			
			.form input {
			width: 100%;
			
			box-sizing: border-box;
			
			padding-left: 10px;
			border: 1px solid #eeeeee;
			
			}
			.form button {
			margin-top: 5px;
			background: #4d59a1;
			color: #fff;
			border: 0;			
			
			width: 97px;
			}
			.form input, .form button{
			height: 40px;
			}
			.button_wrap{
			text-align:right
			}
			h3, h4.name {
			color:#333333;
			}
			.form_head h3, h4.name, .form input, .form button {
			font-size: 14px;
			}
			h4.name {
			line-height: 0.46;
			margin: 0;
			}
			span.phone{
			font-size: 12px;
			color:#666666;
			}
			span.delete {
			display: inline-block;
			padding-left: 5px;
			color: #333333;
			cursor:pointer;
			}
			span.delete:hover{
			color:red
			} 
			.form_head {
			padding: 0 25px;
			
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
			height: 58px;
			box-sizing: border-box;
			display: flex;
			align-items: center;
			}
			
			
			.form_head,.contact {
			border-bottom: 1px solid #eeeeee;
			}
			.contacts .contact:last-child {
			border-bottom: 1px solid #ffffff;
			}
			.contacts {
			margin-top: 20px;
			
			
			}
			.contact_wrap {
			padding: 20px 25px;
			}
			
			
			::-webkit-input-placeholder { /* WebKit, Blink, Edge */
			color:    #b2b2b2;
			}
			:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
			color:    #b2b2b2;
			opacity:  1;
			}
			::-moz-placeholder { /* Mozilla Firefox 19+ */
			color:    #b2b2b2;
			opacity:  1;
			}
			:-ms-input-placeholder { /* Internet Explorer 10-11 */
			color:    #b2b2b2;
			}
			::-ms-input-placeholder { /* Microsoft Edge */
			color:    #b2b2b2;
			}
			
			::placeholder { /* Most modern browsers support this now. */
			color:    #b2b2b2;
			}
			
			.hidden {
			display:none;
			}
		</style>
	</header>
	<body>
		<div class="container">
			<div class="form">	
				<div class="form_head">
					
					<h3>Добавить контакт</h3>
					
				</div>
				<div class="wrap">
					
					
					<form  id = "form" action="ajax.php">
						
						<div class="input_wrap">
							<input type="text" name="name" id="name" placeholder ="Имя" required>
							<!--<label for="name">Введите имя:</label>-->
							
						</div>
						<div class="input_wrap">
							<input type="tel" name="phone" id="phone" placeholder ="Телефон" pattern="[0-9]{1} [0-9]{3} [0-9]{3} [0-9]{2} [0-9]{2}" required>
							<!--<label for="email">Введите email:</label>-->
							
						</div>
						<div class="button_wrap">
				
							<div id="loader" class="hidden"></div>
							<button type="submit">Добавить</button>
							
						</div>
					</form>
				</div>
			</div>	
			
			<div class="contacts">
				<div class="form_head">
					
					<h3>Список контактов</h3>
					
				</div>
				<div class="contacts_list">
				
				</div>
			</div>
			
		</div>
<script>

const input = document.getElementById("phone");

const phoneNumber = [/\D/, ' ', /\D/, /\D/, /\D/, ' ', /\D/, /\D/, /\D/, ' ', /\D/, /\D/, ' ', /\D/, /\D/];

function mask(e, formater) {
  const result = [];
  const { target } = e;
  for (let i = 0; i < target.value.length; i++) {
    if (i >= formater.length) break;
    result[i] =
      (typeof formater[i] === "string" ? formater[i] : "") +
      target.value[i].replace(formater[i], "");
  }
  e.target.value = result.join("");
}

input.addEventListener("input", (e) => mask(e, phoneNumber));


function serializeForm(formNode) {
  const { elements } = formNode;
  const data = Array.from(elements)
    .filter((item) => !!item.name)
    .map((element) => {
      const { name, value } = element;
      return { name, value };
    });
  return data;
}



async function formSubmit(event) {
  event.preventDefault();
  const data = serializeForm(event.target);
  toggleLoader();
  const { status } = await sendData(data);
  toggleLoader();
  form.reset();
  if (status === 200) {
    onSuccess(event.target);
    loadContacts();
  }
}

async function sendData(data) {
  return await fetch("ajax.php", {
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },

    body: JSON.stringify({
      name: data[0].value,
      phone: data[1].value,
    }),
  });
}

const form = document.getElementById("form");
form.addEventListener("submit", formSubmit);

function toggleLoader() {
  const loader = document.getElementById("loader");
  loader.classList.toggle("hidden");
}

const contacts = document.querySelector(".contacts_list");

function clearFormField() {}

function loadContacts() {
  fetch("load.php")
    .then((response) => {
      return response.text();
    })
    .then((text) => {
      const result = JSON.parse(text);
      cleanContacts();
      for (let i = 0; i < result.length; i++) {
        addContactNode(result[i]);
      }
    })
    .then(() => {
      setTimeout(delContactReady, 0);
    })
    .catch((error) => {
      console.log(error);
    });
}

function cleanContacts() {
  contacts.innerHTML = "";
}

function addContactNode(obj) {
  let div = document.createElement("div");
  div.classList.add("contact");
  div.innerHTML = `<div class="contact_wrap">
			<h4 class = "name">${obj.name}<span class="delete" data-id = "${obj.id}">×</span></h4>
			<span class = "phone">${obj.phone}</span>
	</div>`;
  contacts.appendChild(div);
}

function onSuccess(formNode) {
  //	formNode.classList.toggle('hidden')
}
loadContacts();

function delContactReady() {
  let delBtnList = document.querySelectorAll(".delete");

  for (let i = 0; i < delBtnList.length; i++) {
    delBtnList[i].addEventListener("click", function () {
      delContact(delBtnList[i].dataset.id);
    });
  }
}

function delContact(id) {
  fetch(`del.php?id=${id}`)
    .then(() => {
      setTimeout(loadContacts, 0);
    })
    .catch((error) => {
      console.log(error);
    });
}
	
</script>
</body>
</html>





