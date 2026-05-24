function save2() { //funkce pro stazeni obrazku kliknutim na tlacitko
    var gh = canvas.toDataURL('png');
    var a  = document.createElement('a');
    a.href = gh;
    a.download = 'obrazek.png';
    a.click()
}

const canvas = document.getElementById('canvas'); //vyber canvas elementu
const ctx = canvas.getContext('2d');//context je, ze pracujeme s 2d obrazky
const reader = new FileReader();//vybrat soubor

var img = new Image();//vytvoreni noveho obrazku


const uploadImage = e => {
  reader.onload = () => {
    img.onload = () => {
      canvas.width = img.width;//nastaveni canvasu na variabilni delku nacteneho obrazku
      canvas.height = img.height;
      ctx.drawImage(img,0,0)//vymalovani plochy canvasu
    }
    img.src = reader.result; //source je reader, coz je vybrat soubor
  }
  reader.readAsDataURL(e.target.files[0])//prevedeni na url, jinak by se obrazek nezobrazil
}


const imageLoader = document.getElementById('uploader');//priprava pro zmenu obrazku
imageLoader.addEventListener('change',uploadImage)//cekani na zmenu

const prahovani = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)
    const grey = data[i]*0.21 + data[i+1]*0.71 + data[i+2]*0.07;
    data[i] = grey;
    data[i+1] = grey;
    data[i+2] = grey;
  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const sepia = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)
    const grey = data[i]*0.21 + data[i+1]*0.71 + data[i+2]*0.07;
    data[i] = grey + 95;
    data[i+1] = grey + 58;
    data[i+2] = grey;
  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const invert = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)

    data[i] = 255 - data[i];
    data[i+1] = 255 - data[i+1];
    data[i+2] = 255 - data[i+2];
  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const rgb = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)

    data[i] = data[i];
    data[i+1] = data[i+2];
    data[i+2] = data[i+1];
  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const bgr = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)

    data[i] = data[i+1];
    data[i+1] = data[i];
    data[i+2] = data[i+2];
  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const grb = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)

    data[i] = data[i+2];
    data[i+1] = data[i+1];
    data[i+2] = data[i];
  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const r = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)

    data[i] = 255;

  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const g = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)

    data[i+1] = 255;

  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}
const b = () => {
  const imageData = ctx.getImageData(0,0,canvas.width, canvas.height);
  const data = imageData.data
  for (let i = 0; i< data.length; i+=4) { //i+=4 kvuli slozkam (red,green,blue,alpha)

    data[i+2] = 255;

  }
  ctx.putImageData(imageData,0,0);//vlozeni dat do obrazku
}

const clearChanger = () => {//smazani efektu
  img.src = reader.result;
}

document.querySelectorAll('button')[0].addEventListener('click',prahovani);//prirazeni akci ke tlacitkum
document.querySelectorAll('button')[1].addEventListener('click',sepia);
document.querySelectorAll('button')[2].addEventListener('click',invert);
document.querySelectorAll('button')[3].addEventListener('click',rgb);
document.querySelectorAll('button')[4].addEventListener('click',bgr);
document.querySelectorAll('button')[5].addEventListener('click',grb);
document.querySelectorAll('button')[6].addEventListener('click',r);
document.querySelectorAll('button')[7].addEventListener('click',g);
document.querySelectorAll('button')[8].addEventListener('click',b);
document.querySelectorAll('button')[9].addEventListener('click',clearChanger);
