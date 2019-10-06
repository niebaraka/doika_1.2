(function() {
  var toMain = false;

  function loadConfig() {
       AJAXRequest('/doika/client-' + window.parent.doika.campaignId, setConfigHTML)
  }

  function loadDataConfig() {
     AJAXRequest('/doika/client-' + window.parent.doika.campaignId, setConfigData)
  }

  function setConfigData(data) {
      dataConfig = data;
  }

  function setConfigHTML(data) {
    document.getElementsByClassName("module-donate__title")[0].innerText = data.innerText.campaignTitle;
    document.getElementsByClassName("module-donate__description")[0].innerHTML = data.innerText.campaignDescription;
    document.getElementsByClassName("payment__description")[0].innerText = data.innerText.paymentDescriptionTitle;
    document.getElementsByClassName("result__description")[0].insertAdjacentHTML( 'beforeend', data.innerText.resultsText);

    document.getElementById('module-donate').style.backgroundColor = data.backgroundColor;

    document.getElementsByClassName("module-donate__title")[0].style.color = data.titleTextColor;
    document.getElementsByClassName("module-donate__title")[0].style.fontSize = data.titleFontSize;

    document.getElementsByClassName("module-donate__description")[0].style.color = data.descriptionTextColor;
    document.getElementsByClassName("module-donate__description")[0].style.fontSize = data.descriptionFontSize;


    updateIframeHeight();

    window.parent.doika.title = data.innerText.campaignTitle;
    window.parent.doika.result = data.innerText.resultsText;
    window.parent.postMessage(['dockHeader', true], '*')
  }

  function getBePaidJS(data) {

  // document.getElementsByClassName("donate-bePaid__form")[0].innerHTML =
  // '<iframe src="${data.formUrl}" frameborder="0" style="display: block;
	// margin: auto;"></iframe>'
	  window.parent.location.href = data.formUrl;
  }

  function AJAXRequest(url, callback) {
        var request = new XMLHttpRequest();
        request.open('GET', url, true);

        request.onreadystatechange = function() {
          if (request.readyState === 4) {
            if (request.status >= 200 && request.status < 300) {
              var data = JSON.parse(request.responseText);
              return callback(data);
            }
          }
        };
        request.send();
  }

  function back() {
    if(toMain) {
      var paymentGatewayParams = ["status", "statusMessage", "orderId"];
      var queryString = (window.parent.location.search.split('?')[1] || '')
                                  .split('&').filter(param => !paymentGatewayParams.includes(param.split('=')[0]))
                                  .join('&');
	  window.top.location.href = window.parent.location.protocol + '//' + 
	  								window.parent.location.hostname + 
	  								window.parent.location.pathname + 
	  								(queryString.length > 0 ? '?' + queryString : '') + 
	  								'#module-donate-wrapper';

    } else
     window.parent.postMessage(['doikaMain', true], '*')
  }

  function init() {

    switch (window.parent.doika.status) {
      case 'success':
        var bePaidForm = document.querySelector(".donate-bePaid__form");
        var wrapper = document.querySelector(".module-donate__message");
        bePaidForm.style.display = "none";
        wrapper.style.display = "flex";
        var title = document.querySelector(".module-donate__message_title");
        title.innerHTML = "Дзякуй, аплата паспяхова завершана";
        title.style.background = "url(assets/img/success.png) center center no-repeat";
        title.style.backgroundSize = "contain";
        var button = document.querySelector(".module-donate__message_button");
        var backbutton = document.querySelector(".module-donate__back-button");
        backbutton = backbutton.style.display = "none";
        toMain = true;
        document.querySelector(".module-donate__message_button").addEventListener("click", back);
        button.innerHTML = "Мне спадабалася! Ахвяраваць яшчэ.";
        afterPayScroll();
      break;
      case 'fail':
        var bePaidForm = document.querySelector(".donate-bePaid__form");
        var wrapper = document.querySelector(".module-donate__message");
        bePaidForm.style.display = "none";
        wrapper.style.display = "flex";
        var title = document.querySelector(".module-donate__message_title");
        title.style.background = "url(assets/img/fail.png) center center no-repeat";
        title.style.backgroundSize = "contain";
        var statusMessage = window.parent.doika.statusMessage;
        title.innerHTML = 'Прабачце, транзакцыя была адхiлена банкам' + (statusMessage ? ': "' + statusMessage + '"' : '');
        var button = document.querySelector(".module-donate__message_button");
        button.innerHTML = "Паспрабаваць яшчэ раз";
        var backbutton = document.querySelector(".module-donate__back-button");
        backbutton = backbutton.style.display = "none";
        toMain = true;
        document.querySelector(".module-donate__message_button").addEventListener("click", back);
        afterPayScroll();
      break;
      default:
        var backbutton = document.querySelector(".module-donate__back-button");
      	backbutton = backbutton.style.display = "none";
        var backUrl = '&backUrl=' + encodeURIComponent(window.parent.location.href);
        var url = '/doika/donate-' + window.parent.doika.campaignId + '?donate=' + window.parent.doikaSum + backUrl;
        AJAXRequest(url, getBePaidJS);
   }

    document.querySelector(".payment__description").addEventListener("click", PopUpShow);
    document.querySelector(".module-donate__back-button").addEventListener("click", back);

    loadDataConfig();
    loadConfig();
    document.querySelector("#donate-bePaid__form").addEventListener("load", updateIframeHeight);

  }

  window.addEventListener("load", init);

  function PopUpShow() {
    window.parent.postMessage(['openPopUp', true], '*')
  }

  function updateIframeHeight() {
    window.parent.postMessage(['updateIframeHeight', true], '*')
  }

  function afterPayScroll() {
    // возвращает cookie если есть или undefined
    function getCookie( name ) {
      var matches = document.cookie.match( new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
      ));
      return matches ? decodeURIComponent( matches[1] ) : null;
    }

    if( getCookie( 'pageYOffset' ) ) {
      window.parent.scrollTo( 0, getCookie ( 'pageYOffset' ) );
    }
  }
}());
