/*转账*/
var OptLstTxt = new Array;
var OptLstVal = new Array;
var OptLen = 0;
function NoDupl(SelObjFrom, SelObjTo) {
    var OldToVal = SelObjTo.options[SelObjTo.selectedIndex].value;
    if (OptLen == 0) {
        OptLen = SelObjFrom.length;
        for (var i = 1; i < OptLen; i++) {
            OptLstTxt[i] = SelObjFrom.options[i].text;
            OptLstVal[i] = SelObjFrom.options[i].value;
        }
    }
    var j = 1;
    for (var i = 1; i < OptLen; i++) {
        if (OptLstVal[i] != SelObjFrom.options[SelObjFrom.selectedIndex].value) {
            if (j == SelObjTo.length) {
                SelObjTo.options[j] = new Option(OptLstTxt[i]);
            }
            else {
                SelObjTo.options[j].text = OptLstTxt[i];
            }
            SelObjTo.options[j].value = OptLstVal[i];
            if (OptLstVal[i] == OldToVal) {
                SelObjTo.selectedIndex = j;
            }
            j++;
        }
    }
    if (SelObjTo.length > j)
        SelObjTo.options[(SelObjTo.length - 1)] = null;
}