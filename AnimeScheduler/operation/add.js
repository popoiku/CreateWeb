document.addEventListener("DOMContentLoaded", () => {
  
});

// 入力欄増減
const defualtArray = [
                        'animetitle',
                        'animelink',
                        'broadcast0',
                        'y_date0',
                        'm_date0',
                        'd_date0',
                        'h_time0',
                        'm_time0',
                        'h_firsttime0',
                        'm_firsttime0'
                      ];
                      
const addArray = [
                    'broadcast',
                    'y_date',
                    'm_date',
                    'd_date',
                    'h_time',
                    'm_time',
                    'h_firsttime',
                    'm_firsttime'
                  ];
                  
const optionArray = [
                      'year',
                      'month',
                      'day',
                      'hour',
                      'minute',
                      'f_hour',
                      'f_minute'
                    ];

const date = new Date();

let counter = 0;
let counterId = "";

onload = () => {
  
  yearOption(optionArray[0] + counter);
  monthOption(optionArray[1] + counter);
  dayOption(optionArray[2] + counter);
  hourOption(optionArray[3] + counter);
  minuteOption(optionArray[4] + counter);
  hourOption(optionArray[5] + counter);
  minuteOption(optionArray[6] + counter);
  
  optionCopy(optionArray[3] + counter, optionArray[5] + counter);
  optionCopy(optionArray[4] + counter, optionArray[6] + counter);
  
};

const addInput = () => {

  counter += 1;
  counterId = 'iN' + counter;

  const addParent = document.getElementById('tablebody');
  const addChild = document.createElement('tr');

  addChild.innerHTML =  '<tr>' +
                          '<td data-label="放送局">' +
                            '<input type="text" name="' + addArray[0] + counter + '">' +
                          '</td>' +
                          '<td data-label="日付">' +
                            '<select name="'+ addArray[1] + counter + '" id="' + optionArray[0] + counter + '"></select><!-- ' +
                            '--><select name="'+ addArray[2] + counter + '" id="' + optionArray[1] + counter + '"></select><!-- ' +
                            '--><select name="'+ addArray[3] + counter + '" id="' + optionArray[2] + counter + '"></select>' +
                          '</td>' +
                          '<td data-label="時間">' +
                            '<select name="' + addArray[4] + counter + '" id="' + optionArray[3] + counter + '"></select><!-- ' +
                            '--><select name="' + addArray[5] + counter + '" id="' + optionArray[4] + counter + '"></select>' +
                          '</td>' +
                          '<td data-label="時間（初回）">' +
                            '<select name="' + addArray[6] + counter + '" id="' + optionArray[5] + counter + '"></select><!-- ' +
                            '--><select name="' + addArray[7] + counter + '" id="' + optionArray[6] + counter + '"></select>' +
                          '</td>' +
                        '</tr>';
  
  addChild.id = counterId;
  addParent.appendChild(addChild);

  yearOption(optionArray[0] + counter);
  monthOption(optionArray[1] + counter);
  dayOption(optionArray[2] + counter);
  hourOption(optionArray[3] + counter);
  minuteOption(optionArray[4] + counter);
  hourOption(optionArray[5] + counter);
  minuteOption(optionArray[6] + counter);

  optionCopy(optionArray[3] + counter, optionArray[5] + counter);
  optionCopy(optionArray[4] + counter, optionArray[6] + counter);
  
  return false;
};

yearOption = (targetYear) => {
  
  const yearSelect = document.getElementById(targetYear);

  let year = date.getFullYear();

  for(var i = 0; i <= 10; i++){
    let optionYear = document.createElement("option");
    let resultCalcY = (year - 5) + i;
    
    optionYear.value = resultCalcY;
    optionYear.text = resultCalcY;
    
    if(resultCalcY === year){
      optionYear.selected = true;
    }
    
    yearSelect.appendChild(optionYear);
    
  }
   
};

monthOption = (targetMonth) => {
  
  const monthSelect = document.getElementById(targetMonth);
  
  let month = date.getMonth() + 1;
  
  for(var i = 0; i < 12; i++){
    let optionMonth = document.createElement("option");
    let resultCalcM = i + 1;
    
    optionMonth.value = giveZero(resultCalcM);
    optionMonth.text = giveZero(resultCalcM);
    
    if(month === resultCalcM){
      optionMonth.selected = true;
    }
    
    monthSelect.appendChild(optionMonth);
    
  }
  
};

dayOption = (targetDay) => {
  
  const daySelect = document.getElementById(targetDay);
  
  let day = date.getDate();
  
  for(var i = 0; i < 31; i++){
    let optionDay = document.createElement("option");
    let resultCalcD = i + 1;
  
    optionDay.value = giveZero(resultCalcD);
    optionDay.text = giveZero(resultCalcD);
    
    if(day === resultCalcD){
      optionDay.selected = true;
    }
    
    daySelect.appendChild(optionDay);
    
  }
};

hourOption = (targetHour) => {
  
  const hourSelect = document.getElementById(targetHour);
  
  let hour = date.getHours();
  
  for(var i = 0; i <= 23; i++){
    let optionHour = document.createElement("option");
    let resultCalcH = i;
    
    optionHour.value = giveZero(resultCalcH);
    optionHour.text = giveZero(resultCalcH);
    
    hourSelect.appendChild(optionHour);
  }
  
};

minuteOption = (targetMinute) => {
  
  const minuteSelect = document.getElementById(targetMinute);
  
  let minute = date.getMinutes();
  
  for(var i = 0; i <= 59; i++){
    let optionMinute = document.createElement("option");
    let resultCalcM = i;
    
    optionMinute.value = giveZero(resultCalcM);
    optionMinute.text = giveZero(resultCalcM);
    
    minuteSelect.appendChild(optionMinute);

  }
};

giveZero = (num) => {
  
  num += "";
  
  if(num.length === 1){
    num = "0" + num;
  }
  
  return num;
};

optionCopy = (o, c) => {
  
  const originalOption = document.getElementById(o);
  const copyOption = document.getElementById(c);
  
  originalOption.addEventListener("change", () => {
    copyOption.value = originalOption.value;
  });
  
};

const deleteInput = () => {

  if (counter !== 0) {

    const deleteParent = document.getElementById('tablebody');
    const deleteChild = document.getElementById(counterId);
    deleteParent.removeChild(deleteChild);

    counter--;
    counterId = 'iN' + counter;
    
    return false;
  }

};

formCheck = () => {

  let judg = 0;

  // デフォルト表示の入力欄チェック
  for(let i = 0; i < defualtArray.length; i++){

    let target = document.getElementsByName(defualtArray[i]);
    let targetResult = target[0].value;

    if (targetResult === "") {
      judg++;
    } else if(!targetResult.match(/\S/g)) {
      judg++;
    }

  }
  
  /*--------------------------------------------------------------------------
     修正前、getElementsByNameが検知するvalueが多すぎたことが原因で
     読み込みが追い付かなくてエラーが起きていた。
     そのため、例外処理を一時的に付与。
     エラーが頻繁に起こるようならalertを付ける。
  --------------------------------------------------------------------------*/
  try {
  
    // 追加した入力欄チェック
    if (counter > 0) {
  
      for (let i = 1; i <= counter; i++){
        
        let val = document.getElementsByName("broadcast" + [i]);
        let valResult = val[0].value;
        
        if (valResult === "") {
          judg++;
        } else if(!valResult.match(/\S/g)){
          judg++;
        }
  
      }
    
    }
    
  } catch (e) {
    console.error(e.message);
  }

  if (judg > 0) {
    alert("入力されていない箇所があります");
    return false;
  } else {
    const confirmResult = confirm("送信します。よろしいですか？");
    return confirmResult;
  }

};

