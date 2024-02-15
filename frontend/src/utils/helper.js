import moment from 'moment';
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'
import Store from "../store";
import { useLoading } from 'vue-loading-overlay';

export const formatDate = date => date ? moment(date).format('DD/MM/YYYY') : '';

export const formatDateTime = dateTime => dateTime ? moment(dateTime).format('DD/MM/YYYY, hh:mm A') : '';

export const formatMonthYear = dateString => {
    const dateObject = moment(dateString, 'YYYY-MM');
    return dateObject.isValid() ? dateObject.format('MMMM, YYYY') : '';
};

export const formatMonthYearWithTime = dateString => {
    const dateObject = moment(dateString);

    return dateObject.isValid() ? dateObject.format('DD/MM/YYYY, HH:mm:ss') : '';
};
const $loading = useLoading();


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

  /**
 * Export HTML table content to a PDF document with customizable options.
 * @param {string} businessUnit - Used for incorporating the logo relevant to the business unit.
 * @param {string} pageOridentation - Page orientation ('l' / 'landscape' / 'p' / 'portrait').
 * @param {string} heading - Caption or title of the PDF document.
 * @param {string} tableId - ID of the HTML table from which data is extracted.
 * @param {Array<number>} leftAlign - Array containing column indices that need left alignment.
 * @param {Array<number>} rightAlign - Array containing column indices that need right alignment.
 * @param {boolean} [useAllPageLogoAndHeading=false] - Whether to display the logo and heading on all pages. Possible values: true or false.
 * @param {boolean} [useSignature=false] - Whether to include a signature section. Possible values: true or false.
 * @param {boolean} [useAllPageTableHeading=true] - Whether to display the table heading on all pages. Possible values: true or false.
 */
export const indexPdfExport = ( businessUnit, pageOridentation, heading, tableId, leftAlign, rightAlign, useAllPageLogoAndHeading = false, useSignature = false, useAllPageTableHeading = true) => {

    const loader = $loading.show({ 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' });
    
    try {
        let doc = new jsPDF(pageOridentation, 'pt', 'a4', true);
        let pageSize = doc.internal.pageSize;
        let pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();
        let pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
        let totalPagesExp = '{total_pages_count_string}';

        let base64Img;
        imgToBase64(Store.getters.getCompanyLogo.logo, function(base64) {
            base64Img = base64;
            if(!useAllPageLogoAndHeading){ // first page logo & title
                if (base64Img) {
                    doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80, undefined, 'FAST');
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
                // tableWidth: 'wrap',
                headStyles: {
                    fillColor : '#F2F2F7',
                    lineWidth: 0.5,
                    lineColor: '#E2E4E8',
                    textColor: '#4c4f52',
                    halign: 'center'
                },
                bodyStyles: {
                    halign: 'center',
                    valign: 'middle'
                },
                didDrawPage: function(data) {
                    // All Page Logo & Heading start
                    if(useAllPageLogoAndHeading){
                        if (base64Img) {
                            doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80, undefined, 'FAST');
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
    } catch (error) {
        console.log(error);
    } finally {
        loader.hide();
    }


}


/**
 * Export content from multiple HTML tables to a PDF document with customizable options.
 * @param {string} businessUnit - Used for incorporating the logo relevant to the business unit.
 * @param {string} pageOridentation - Page orientation ('l' or 'landscape' or 'p' or 'portrait').
 * @param {string} heading - Caption or title of the PDF document.
 * @param {Array<string>} tableIds - IDs of the HTML tables from which data is extracted.
 * @param {boolean} [useAllPageLogoAndHeading=false] - Whether to display the logo and heading on all pages. Possible values: true or false.
 * @param {boolean} [useSignature=false] - Whether to include a signature section. Possible values: true or false.
 */
export const showPdfExport = ( businessUnit, pageOridentation, heading, tableIds, useAllPageLogoAndHeading = false, useSignature = false) => {

    const loader = $loading.show({ 'can-cancel': false, 'loader': 'dots', 'color': '#7e3af2' });
    
    try {
        let doc = new jsPDF(pageOridentation, 'pt', 'a4', true);
        let pageSize = doc.internal.pageSize;
        let pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();
        let pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
        let totalPagesExp = '{total_pages_count_string}';
        let currentPage = 0;

        let base64Img;
        imgToBase64(Store.getters.getCompanyLogo.logo, function(base64) {
            base64Img = base64;
            if(!useAllPageLogoAndHeading){ // first page logo & title
                if (base64Img) {
                    doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80, undefined, 'FAST');
                }
                doc.text((pageWidth / 2) - ((doc.getStringUnitWidth(heading) * doc.internal.getFontSize()) / 2), 115, heading);
            }

            // let res = doc.autoTableHtmlToJson(document.getElementById(tableId), true );
            for (var j = 0; j < tableIds.length; j++) {

            var finalY = doc.lastAutoTable.finalY;
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
                        if (base64Img) {
                            doc.addImage(base64Img, 'JPEG', pageWidth/2 - 40, 10, 80, 80, undefined, 'FAST');
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
            
    } catch (error) {
        console.log(error);
    } finally {
        loader.hide();
    }

}

export const tableToExcel = (tableId, name) => {

    let template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="https://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>'+name+'</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body>';
    let tab_text = template + "<table border='2px'><tr>";
    let tab = document.getElementById(tableId); // id of table

    for (let j = 0; j < tab.rows.length; j++) {
        let row = tab.rows[j];
        for (let i = 0; i < row.cells.length - 1; i++) { // Exclude last cell
            tab_text += row.cells[i].outerHTML;
        }
        tab_text += "</tr><tr>"; // Close the row after excluding last cell
    }

    tab_text += "</tr></table></body></html>";

    let a = document.createElement('a');
    let data_type = 'data:application/vnd.ms-excel';
    a.href = data_type + ', ' + encodeURIComponent(tab_text);
    a.download = name + '.xls';
    a.click();

}

