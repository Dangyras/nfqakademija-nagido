document.addEventListener('DOMContentLoaded', () => {
    windowSize();
    // showArticle(1);
});
//
// function showArticle(articleID) {
//     for (let i = 1; i <= 12; i++) {
//         let article = document.getElementById(i);
//         let sideNavigation = document.getElementById('sideNavigation');
//         if (i === articleID) {
//             article.style.display = 'block';
//             if (document.body.clientWidth < 1100) {
//                 sideNavigation.style.visibility = 'hidden';
//             }
//             sideNavigation.scrollTop = 0;
//         } else {
//             article.style.display = 'none';
//         }
//     }
//     let smallTable = document.getElementById('smallTable');
//     let rows = smallTable.getElementsByTagName('tr').length;
//     for (i = 1; i <= rows; i++) {
//         let x = document.getElementById('displayMoreData' + i);
//         let y = document.getElementById('imgClickAndChange' + i);
//         x.style.display = 'none';
//         y.src = 'images/show-more-button.png';
//         y.title = 'Rodyti daugiau';
//     }
// }

window.addEventListener("resize", windowSize);

function windowSize() {
    let bodyWidth = document.body.clientWidth;
    let sideNavigation = document.getElementById('sideNavigation');
    let main = document.getElementById('main');

    if (bodyWidth >= 1100) {
        let sideWidth;
        if (sideNavigation.style.width === '250px' || sideNavigation.style.width === '') {
            sideWidth = 250;
        } else {
            sideWidth = 0;
        }
        let mainWidth = bodyWidth - sideWidth;
        main.style.marginLeft = sideWidth + 'px';
        main.style.width = mainWidth + 'px';
        sideNavigation.style.visibility = 'visible';
    } else if (bodyWidth < 1100) {
        main.style.marginLeft = '0px';
        main.style.width = bodyWidth + 'px';
        sideNavigation.style.visibility = 'hidden';
        sideNavigation.scrollTop = 0;
    }

    let searchForm = document.getElementById('searchForm');

    if (bodyWidth >= 992) {
        searchForm.style.display = "inline-block";
        searchForm.style.marginLeft = '-4px';
        searchForm.style.width = '520px';
        document.getElementById('searchInput').style.width = '470px';
    } else if (bodyWidth < 992) {
        searchForm.style.display = 'none';
    }
}

document.querySelectorAll('.menuButton').forEach((e) => { e.addEventListener('click', openNav); });

function openNav() {
    let bodyWidth = document.body.clientWidth;
    let sideNavigation = document.getElementById('sideNavigation');
    let main = document.getElementById('main');

    if (bodyWidth >= 1100) {
        let sideWidth;
        if (sideNavigation.style.width === '250px' || sideNavigation.style.width === '') {
            sideWidth = 0;
        } else {
            sideWidth = 250;
        }
        let mainWidth = bodyWidth - sideWidth;
        main.style.marginLeft = sideWidth + 'px';
        main.style.width = mainWidth + 'px';
        sideNavigation.style.width = sideWidth + 'px';
    } else if (bodyWidth < 1100) {
        main.style.marginLeft = '0px';
        main.style.width = bodyWidth + 'px';
        sideNavigation.style.width = '250px';
        sideNavigation.style.visibility = 'visible';

        // window.addEventListener('click', function(event) {
        //     if (event.pageX > 250 && bodyWidth < 1100) {
        //         sideNavigation.style.visibility = 'hidden';
        //     }
        // });
    }
    sideNavigation.scrollTop = 0;
}

document.querySelectorAll('.searchButton').forEach((e) => { e.addEventListener('click', displaySearchInput); });

function displaySearchInput() {
    let searchForm = document.getElementById('searchForm');

    searchForm.style.display = 'inline-block';

    let searchInput = document.getElementById('searchInput');
    let bodyWidth = document.body.clientWidth;

    if (bodyWidth < 600) {
        searchForm.style.marginLeft = '-130px';
        searchForm.style.width = '230px';
        searchInput.style.width = '170px';
    } else if (bodyWidth >= 600 && bodyWidth < 992) {
        searchForm.style.marginLeft = '-260px';
        searchForm.style.width = '400px';
        searchInput.style.width = '340px';
    } else if (bodyWidth >= 992) {
        searchForm.style.marginLeft = '-4px';
        searchForm.style.width = '520px';
        searchInput.style.width = '470px';
    }
}

document.querySelectorAll('.addDocumentButton').forEach((e) => { e.addEventListener('click', addDocument); });

function addDocument() {
    let x = document.getElementById('newDocumentWindow');
    x.style.display = 'block';
}

function display() {
    let x = document.getElementById('newDocumentWindow');
    x.style.display = 'block';
}

document.querySelectorAll('#document_cancel').forEach((e) => { e.addEventListener('click', goBack); });
document.querySelectorAll('#edit-document_cancel').forEach((e) => { e.addEventListener('click', goBack); });

function goBack() {
    let x = document.getElementById('newDocumentWindow');
    x.style.display = 'none';
}

// document.querySelectorAll('.clickAndChange').forEach((e) => { e.addEventListener('click', toggleRR); });

// function toggleRR() {
//
//     toggle(this.dataset.rowNr);
// }

function toggle(i) {
    let x = document.getElementById('displayMoreData' + i);
    let y = document.getElementById('imgClickAndChange' + i);
    if (x.style.display === 'none' || x.style.display === '') {
        x.style.display = 'table-row';
        y.src = 'images/show-less-button.png';
        y.title = 'Rodyti mažiau';
    } else {
        x.style.display = 'none';
        y.src = 'images/show-more-button.png';
        y.title = 'Rodyti daugiau';
    }
}

// for(let i = 0; i < 2; i++)
// {
//     let table = document.getElementsByTagName('table')[i];
//
//     if (table) {
//         table.addEventListener('click', (e) => {
//             if (e.target.className === 'delete') {
//                 const id = e.target.getAttribute('data-id');
//                 fetch(`/delete/${id}`, {
//                     method: 'DELETE'
//                 }).then(res => window.location.reload());
//             }
//
//             if (e.target.className === 'edit') {
//                 const id = e.target.getAttribute('data-id');
//                 $.ajax({
//                     url:        '/',
//                     type:       'POST',
//                     async:      true,
//                     data: {
//                         id: id
//                     },
//                     success: function(data) {
//                         $('#document_documentName').val(data.documentName);
//                         $('#document_documentDate').val(data.documentDate);
//                         $('#document_documentExpires').val(data.documentExpires);
//                         let reminder = $('#document_documentReminder');
//                         if(reminder) {
//                             reminder.val(data.documentReminder);
//                         }
//                         let notes = $('#document_documentNotes');
//                         if (notes) {
//                             notes.val(data.documentNotes);
//                         }
//                         $('#form_category_id option[value= '+ data.categoryId +']').prop('selected', true);
//                         //display();
//                     },
//                 });
//             }
//         });
//     }
// }
