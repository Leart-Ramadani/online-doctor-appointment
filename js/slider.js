const docTest = document.querySelectorAll('.doc_info');
const slidebtn = document.querySelector('.nextBtn');
const prevBtn = document.querySelector('.prevDoc');

let tab = 0;

const showTabs = (x) => {
    if (x <= docTest.length) {
        docTest[x].style.display = 'inline-block';
        docTest[x + 1].style.display = 'inline-block';
        docTest[x + 2].style.display = 'inline-block';
        docTest[x + 3].style.display = 'inline-block';
    }

}
showTabs(tab);

const nextTab = () => {
    if (tab < docTest.length - 4) {
        docTest[tab].style.display = 'none';
        tab++;
        showTabs(tab);
    } else {
        docTest[tab].style.display = 'none';
        docTest[tab + 1].style.display = 'none';
        docTest[tab + 2].style.display = 'none';
        docTest[tab + 3].style.display = 'none';

        tab = 0;
        showTabs(tab);
    }
}

const prevTab = () => {
    if (tab > 0) {
        docTest[tab + 3].style.display = 'none';
        tab--;
        showTabs(tab);
    } else {

        docTest[tab].style.display = 'none';
        docTest[tab + 1].style.display = 'none';
        docTest[tab + 2].style.display = 'none';
        docTest[tab + 3].style.display = 'none';
        tab = docTest.length - 4;
        showTabs(tab);
    }
}


slidebtn.onclick = nextTab;
prevBtn.onclick = prevTab;
