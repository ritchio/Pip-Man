var APIRequestCache = {};
var urlExceptionList = ['#','logout.php'];

window.onload = function(){

    //redirect the user to the last url requested
    const last_url = sessionStorage.getItem("last_url");
     console.log(last_url);
    if(typeof(last_url) !== 'undefined' && last_url != null){
        let last_url_object = JSON.parse(last_url);
        getContent({url:last_url_object.url,method:last_url_object.method,data:last_url_object.data,target:last_url_object.target});
    }

}

document.addEventListener('submit', function (event) {

  const form = event.target;
console.log(form);
  event.preventDefault();

  console.log(`Interceptado formulario con ID: ${form.id || 'Sin ID'}`);

  // 3. Recolectamos los datos de una forma ultra sencilla
  const formData = new FormData(form);
  
  // Puedes convertirlo a un objeto clásico de JS si vas a enviar JSON
  const data = Object.fromEntries(formData.entries());
  console.log('Datos capturados:', data);
  console.log(form["enctype"]);

  getContent({
    url:`page.php?q=${form.getAttribute("action")}`,
    target:form.target,
    method: "post",
    data:formData
  });
  
});

document.addEventListener('click', function (event) {
    const link = event.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href');
    const target = link.getAttribute('target');

    if (!href || href.startsWith('javascript:') || urlExceptionList.includes(href)) return;

    if(link.dataset.confirm){
        event.preventDefault();
        confirmAction({
            message: link.dataset.confirm,
            onConfirm: () => {
                getContent({ url: `page.php?q=${href}`, target: target });
            }
        });
    }else{
        event.preventDefault();
        getContent({ url: `page.php?q=${href}`, target: target });
    }
});

async function getContent({ url, method = 'GET', data = null , target = null , headers = null}) {

    let loading_div = document.getElementById("loading");
    loading_div.style.display = "block";
    if(url.indexOf("page.php") == -1){
        url = "page.php?q="+url;
    }
    method = method.toUpperCase();

    if(method == 'GET' && data != null){
        method = 'POST';
    }

    const cacheKey = `${method}:${url}:${JSON.stringify(data)}`;

    let last_url = {url:url,method:method,target:target};
    sessionStorage.setItem("last_url",JSON.stringify(last_url));

    try {
        const options = { method };
        if (data) {
  
            
            if(data instanceof FormData){
                console.log('es formdata');
                options.headers = {'X-Requested-With': 'HTMLFragment'};
                options.body = data;

            }else{
                options.headers = {'X-Requested-With': 'HTMLFragment','Content-Type':'application/json'};
                options.body = JSON.stringify(data);
            }
        }else{
            options.headers = { 'X-Requested-With': 'HTMLFragment' };
        }
        const response = await fetch(url, options);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const content = await response.text();
        APIRequestCache[cacheKey] = content;
        updateContent(content, target);
        loading_div.style.display = "none";
    } catch (error) {
        console.error('Error fetching content:', error);
        console.log(target)
        updateContent('<p class="error">Failed to load content. Please try again later.</p>', target);
    }   
}

updateContent = (content, target) => {
    if (target) {
        const targetElement = document.getElementById(target);
        if (targetElement) {
            targetElement.innerHTML = content;

            //if script tag is detected, re-append it
            const scripts = targetElement.querySelectorAll('script');
            if(scripts != null){
                scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                
                Array.from(oldScript.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });
                
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                
                targetElement.appendChild(newScript);
                
                oldScript.remove();
            });
            }
            
            if(target == 'modal'){
                document.getElementById('modalFade').style.display = 'flex';
            }

        }
    }
}

function closeModal(){
    document.getElementById('modalFade').style.display = 'none';
    document.getElementById('modal').innerHTML='';
}

function confirmAction({message, onConfirm, onCancel}){
    const modalContent = `
        <div class="card">
            <div class="card-header">Confirmation</div>
            <div class="card-body">${message}</div>
            <div class="card-footer">
                <button id="confirmBtn">Confirm</button>
                <button id="cancelBtn">Cancel</button>
            </div>
        </div>
    `;
    updateContent(modalContent, 'modal');
    document.getElementById('confirmBtn').onclick = () => {
        if(onConfirm) onConfirm();
        closeModal();
    };
    document.getElementById('cancelBtn').onclick = () => {
        if(onCancel) onCancel();
        closeModal();
    };
}