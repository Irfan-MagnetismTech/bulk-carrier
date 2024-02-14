import moment from 'moment';
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';

export const formatDateTime = dateTime => dateTime ? moment(dateTime).format('DD/MM/YYYY, hh:mm A') : '';

export const showErrorAlert = messages => {
    if (messages.value.length > 0) {
        let rawHtml = ` <ul class="text-left list-disc text-red-500 mb-3 px-5 text-base"> `;
        if (Object.keys(messages.value).length) {
            for (const property in messages.value) {
                rawHtml += `<li> ${messages.value[property]} </li>`
            }
            rawHtml += `</ul>`;

            Swal.fire({
                icon: "",
                title: "Correct Please!",
                html: `
            ${rawHtml}
                    `,
                customClass: "swal-width",
            });
            return false;
        }
    } else {
        return true;
    }
}

function imgToBase64(url, callback) {
    if (!window.FileReader) {
      callback(null);
      return;
    }
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function() {
      var reader = new FileReader();
      reader.onloadend = function() {
        callback(reader.result.replace('text/xml', 'image/jpeg'));
      };
      reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.send();
  }

export const indexPdfExport = ( businessUnit, pageOridentation, heading, tableId, leftAlign, rightAlign, useAllPageLogoAndHeading = false, useSignature = false, useAllPageTableHeading = true) => {
    let doc = new jsPDF(pageOridentation, 'pt', 'a4');
    let pageSize = doc.internal.pageSize;
    let pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();
    let pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
    let totalPagesExp = '{total_pages_count_string}';

    let base64Img;
    imgToBase64('../torony-logo.png', function(base64) {
        base64Img = base64;
        if(!useAllPageLogoAndHeading){ // first page logo & title
            if (base64Img) {
                doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80);
            }
            doc.text((pageWidth / 2) - ((doc.getStringUnitWidth(heading) * doc.internal.getFontSize()) / 2), 115, heading);
        }

        let res = doc.autoTableHtmlToJson(document.getElementById(tableId), true );
        
        doc.autoTable({
            head: [res.columns.slice(0, -1)],
            body: res.data,
            theme: 'grid', 
            margin: {
                top: useAllPageLogoAndHeading ? 130 : 0,
                bottom: useSignature ? 100 : 0
            },
            startY: !useAllPageLogoAndHeading ? 130 : '',
            rowPageBreak: 'auto',
            useCss: true, 
            headStyles: {
                fillColor : '#F2F2F7',
                lineWidth: 0.5,
                lineColor: '#E2E4E8',
                textColor: '#4c4f52',
                halign: 'center'
            },
            bodyStyles: {
                halign: 'center',
            },
            didDrawPage: function(data) {
                // All Page Logo & Heading start
                if(useAllPageLogoAndHeading){
                    if (base64Img) {
                        doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80);
                    }
                    doc.text((pageWidth / 2) - ((doc.getStringUnitWidth(heading) * doc.internal.getFontSize()) / 2), 115, heading);
                }
                // All Page Logo & Heading end

                //signature Start
                let startSignX, signWidth, signRectY = pageHeight - 90, rectHeight = 60, gap = 8, total_sign = 4;
                let rectWidth = (pageWidth - data.settings.margin.left - data.settings.margin.right - (gap * (total_sign + 1))) / total_sign;
                doc.setLineWidth(1);
                doc.setFontSize(12);

                if(useSignature){
                    for (let i = 1; i <= total_sign; i++) {
                        startSignX = data.settings.margin.left + gap + (i - 1) * (rectWidth + gap);
                        doc.rect(startSignX, signRectY, rectWidth, rectHeight);
                        doc.line(startSignX + 2, signRectY + 40, startSignX + rectWidth - 2, signRectY + 40);
        
                        let signText = "Signature" + i;
                        let textWidth = doc.getStringUnitWidth(signText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
                        let startTextX = ((startSignX + (startSignX + rectWidth)) - textWidth) / 2;
        
                        // console.log("startSignX =>", startSignX, "rectWidth =>", rectWidth, "textWidth=>", textWidth, "startTextX =>", startTextX);
                        doc.text(signText, startTextX, signRectY + 52);
                    }
                }
                // Signature End

                // Page Number Start
                let str = 'Page ' + doc.internal.getNumberOfPages();
                if (typeof doc.putTotalPages === 'function') {
                    str = str + ' of ' + totalPagesExp
                }
                doc.setFontSize(10);
                doc.text(str, data.settings.margin.left, pageHeight - 10);
                // Page Number End

            },
            didParseCell: function (data) {
                if (leftAlign.indexOf(data.column.dataKey) != -1  && data.row.section === 'body') {
                  data.cell.styles.halign = 'left';
                }            
                else if (rightAlign.indexOf(data.column.dataKey) != -1  && data.row.section === 'body') {
                  data.cell.styles.halign = 'right';
                }
                
            },
            willDrawCell: function (data) {
                if (data.row.section === 'head') {
                    if (data.pageCount > 1 && !useAllPageTableHeading) {
                        return false;
                    }
                }
                
            },
        });
        if (typeof doc.putTotalPages === 'function') {
            doc.putTotalPages(totalPagesExp)
        }
        doc.save(`${ heading.split(' ').join('_') }.pdf`)
    });

}

export const showPdfExport = ( businessUnit, pageOridentation, heading, tableIds, useAllPageLogoAndHeading = false, useSignature = false) => {
    let doc = new jsPDF(pageOridentation, 'pt', 'a4');
    let pageSize = doc.internal.pageSize;
    let pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();
    let pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
    let totalPagesExp = '{total_pages_count_string}';
    let currentPage = 0;

    let base64Img;
    imgToBase64('../../../torony-logo.png', function(base64) {
        base64Img = base64;
        if(!useAllPageLogoAndHeading){ // first page logo & title
            if (base64Img) {
                doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80);
            }
            doc.text((pageWidth / 2) - ((doc.getStringUnitWidth(heading) * doc.internal.getFontSize()) / 2), 115, heading);
        }

        // let res = doc.autoTableHtmlToJson(document.getElementById(tableId), true );
        for (var j = 0; j < tableIds.length; j++) {

        var finalY = doc.lastAutoTable.finalY;
        console.log("finalY: " + finalY);
        console.log(doc.internal, doc.settings);
        doc.autoTable({
            html: `#${tableIds[j]}`,
            useCss: true,
            pageBreak: 'avoid',
            margin: {
                top: useAllPageLogoAndHeading ? 130 : 40,
                bottom: useSignature ? 100 : 40
            },
            startY: !useAllPageLogoAndHeading ? (finalY ? finalY+20 : 130) : (finalY||130) +20,
            didDrawPage: function(data) {
                // All Page Logo & Heading start
                if(useAllPageLogoAndHeading && currentPage < doc.internal.getNumberOfPages()){
                    // console.log("object"+ currentPage);
                    if (base64Img) {
                        doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80);
                    }
                    doc.text((pageWidth / 2) - ((doc.getStringUnitWidth(heading) * doc.internal.getFontSize()) / 2), 115, heading);
                }
                // All Page Logo & Heading end

                //signature Start
                let startSignX, signWidth, signRectY = pageHeight - 90, rectHeight = 60, gap = 8, total_sign = 4;
                let rectWidth = (pageWidth - data.settings.margin.left - data.settings.margin.right - (gap * (total_sign + 1))) / total_sign;
                doc.setLineWidth(1);
                doc.setFontSize(12);

                if(useSignature && currentPage < doc.internal.getNumberOfPages()){
                    for (let i = 1; i <= total_sign; i++) {
                        startSignX = data.settings.margin.left + gap + (i - 1) * (rectWidth + gap);
                        doc.rect(startSignX, signRectY, rectWidth, rectHeight);
                        doc.line(startSignX + 2, signRectY + 40, startSignX + rectWidth - 2, signRectY + 40);
        
                        let signText = "Signature" + i;
                        let textWidth = doc.getStringUnitWidth(signText) * doc.internal.getFontSize() / doc.internal.scaleFactor;
                        let startTextX = ((startSignX + (startSignX + rectWidth)) - textWidth) / 2;
        
                        // console.log("startSignX =>", startSignX, "rectWidth =>", rectWidth, "textWidth=>", textWidth, "startTextX =>", startTextX);
                        doc.text(signText, startTextX, signRectY + 52);
                    }
                }
                // Signature End

                // Page Number Start
                if(currentPage < doc.internal.getNumberOfPages()){
                    let str = 'Page ' + doc.internal.getNumberOfPages();
                    if (typeof doc.putTotalPages === 'function') {
                        str = str + ' of ' + totalPagesExp
                    }
                    doc.setFontSize(10);
                    doc.text(str, data.settings.margin.left, pageHeight - 10);
                }
            currentPage = doc.internal.getNumberOfPages();
                // Page Number End

            },
        });

        
        
        

    }
    if (typeof doc.putTotalPages === 'function') {
        doc.putTotalPages(totalPagesExp)
    }
        doc.save(`${ heading.split(' ').join('_') }.pdf`)
    });

}

