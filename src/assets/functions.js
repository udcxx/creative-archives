/**
 * 一覧画面を初期化します
 */
function init() {
    initFilterEvent();
    listRefresh(dataSet);

    document.querySelector('.sidebar_togglebutton').addEventListener('click', () => {
        sidebarToggleButton();
    });
};


/**
 * サイドバーのトグルボタンをクリックしたときの処理
 */
function sidebarToggleButton() {
    document.querySelector('.sidebar').classList.toggle('close');
    document.querySelector('.sidebar_togglebutton').classList.toggle('close');
}

/**
 * 一覧を作成します
 * 
 * @param {object} data 一覧に表示するレコード
 */
function listRefresh(data) {
    const records = data.records;
    const listArea = document.querySelector('.list_area');
    listArea.innerHTML = '';

    records.forEach((record) => {
        const size = record.CREATIVE_SIZE.value == 'sp' ? 'sp' : 'pc';
        let imagePath = 'secret.jpg';
        if (record.CREATIVE_TITLE.value != 'シークレット') {
            imagePath = `${record.CREATIVE_CODE.value}-${size}-s.jpg`;    
        }
        const item = listItem(record.CREATIVE_TITLE.value, record.CREATIVE_CODE.value, imagePath);
        listArea.appendChild(item);
    });

    if (!listArea.hasChildNodes()) {
        listArea.innerHTML = '<p class="nodata">😥<br>データがありません<br>絞り込み条件を見直してください</p>'
    }
}



/**
 * 一覧画面での1つ分のコンポーネントを生成します
 * 
 * @param {string} title クリエイティブ名
 * @param {number} id クリエイティブのID
 * @param {string} image 画像のファイル名
 * 
 * @return {Element} クリエイティブ1つ分のコンポーネント
 */
function listItem(title, id, image) {
    let box = document.createElement('a');
    box.classList.add('item');
    box.href = './details/?creativecode=' + id;
    box.style.backgroundImage = `url("./data/${image}")`;

    titleBox = document.createElement('div');
    titleBox.classList.add('item--title');
    titleBox.innerText = title;
    
    box.appendChild(titleBox);

    return box;
}



/**
 * 絞り込み条件が変更されたときに発火するイベントです
 */
function initFilterEvent() {
    const conditions = document.querySelectorAll('.filter input[type="checkbox"]');

    conditions.forEach((condition) => {
        condition.addEventListener('change', () => {

            let newDataSet = {"records":[]}

            dataSet.records.forEach((data) => {
                if( isInArr(data.CREATIVE_TAG.value, selectedCondition('tags')) &&
                    isInArr(data.CREATIVE_CATEGORY.value, selectedCondition('category')) &&
                    isInArr(data.CREATIVE_SIZE.value, selectedCondition('size')) ) {

                    if (selectedCondition('secret')[0] === 'PRIVATE') {
                        newDataSet.records.push(data);
                    } else {
                        if (data.CREATIVE_PUBLIC.value === 'PUBLIC') {
                            newDataSet.records.push(data);   
                        }
                    }
                }
            });

            listRefresh(newDataSet);
        });
    });
};



/**
 * 条件ごとの現在選択されている項目を配列で返します
 * 
 * @param {string} conditionType secret / tags / category / size
 * @return 選択されている項目 
 */
function selectedCondition(conditionType) {
    const checkboxes = document.querySelectorAll(`.filter .${conditionType} input[type="checkbox"]:checked`);
    let array = [];

    checkboxes.forEach((checkbox) => {
        array.push(checkbox.getAttribute('data-content'));
    });

    return array;
}



/**
 * 配列同士を比較して、1つでも共通の値があればTrueを返します
 * 
 * @param {Array} arr1 
 * @param {Array} arr2 
 * @return Boolean
 */
function isInArr(arr1, arr2) {
    return [...arr1, ...arr2].filter(item => arr1.includes(item) && arr2.includes(item)).length > 0
};



/**
 * 詳細画面を生成します
 * 
 * @param {Number} creativecode 
 */
function initDetails(creativecode) {
    thisData = new Object;
    dataSet.records.forEach((data) => {
        if(data.CREATIVE_CODE.value === creativecode) {
            thisData = data;
        }
    });

    if (Object.keys(thisData).length  === 0) {
        // 見つからない場合
        document.querySelector('main').innerHTML = '<p class="nodata">😥<br>データが見つかりませんでした。<br><a href="../">&laquo; 一覧画面に戻る</a></p>'
    } else {
        if (thisData.CREATIVE_TITLE.value === 'シークレット') {
            document.querySelector('.main--title').innerText = 'シークレット';
            document.querySelector('.main--text').innerHTML = `シークレット（非公開）のクリエイティブは、認証コードでログインした方のみ閲覧可能です。<br>認証コードをお持ちの方は <a href="../login/?creativecode=${creativecode}">ログインページ</a> からログインしてください。`;
        } else {
            document.querySelector('.main--requester').innerText = thisData.CREATIVE_REQUESTER.value ? thisData.CREATIVE_REQUESTER.value : '';
            document.querySelector('.main--title').innerText = thisData.CREATIVE_TITLE.value ? thisData.CREATIVE_TITLE.value : '';
            document.querySelector('.main--url').innerText = thisData.CREATIVE_URL.value ? thisData.CREATIVE_URL.value : '';
            document.querySelector('.main--text').innerText = thisData.CREATIVE_TEXT.value ? thisData.CREATIVE_TEXT.value : '';

            document.querySelector('.image--pc').innerHTML = `<img src="../data/${creativecode}-pc-f.jpg">`;
            document.querySelector('.image--sp').innerHTML = `<img src="../data/${creativecode}-sp-f.jpg">`;

            sizeToggleEvent();
        }

        if (thisData.CREATIVE_SIZE.value === 'レスポンシブ' || thisData.CREATIVE_SIZE.value === 'PC/SP') {
            document.getElementById('size').addEventListener('change', () => {sizeToggleEvent()});
        } else {
            document.querySelector('.size .toggleselect--box').innerHTML = '';
            document.querySelector('.size.info--item').appendChild(makeTagContent(thisData.CREATIVE_SIZE.value));

            if (thisData.CREATIVE_SIZE.value === 'PC') {
                document.querySelector('.main--image.image--pc').style.display = 'block';
                document.querySelector('.main--image.image--sp').style.display = 'none';
            } else {
                document.querySelector('.main--image.image--pc').style.display = 'none';
                document.querySelector('.main--image.image--sp').style.display = 'block';
            }
        }

        thisData.CREATIVE_TAG.value.forEach((tag) => {
            document.querySelector('.tags.info--item').appendChild(makeTagContent(tag));
        });

        document.querySelector('.category.info--item').appendChild(makeTagContent(thisData.CREATIVE_CATEGORY.value));
    }

    document.querySelector('.sidebar_togglebutton').addEventListener('click', () => {
        sidebarToggleButton();
    });
}



/**
 * 詳細画面で、タグ・種別の各項目を生成します
 * 
 * @param {String} content 項目名
 * 
 * @return element
 */
function makeTagContent(content) {
    const span = document.createElement('span');
    span.innerText = content;
    span.classList.add('info--content');
    
    return span
}



/**
 * 詳細画面で、クリエイティブの画面サイズを切り替えたときに発火するイベントです
 */
function sizeToggleEvent() {
    if (document.getElementById('size').checked == true) {
        // True = SP
        document.querySelector('.main--image.image--pc').style.display = 'none';
        document.querySelector('.main--image.image--sp').style.display = 'block';
    } else {
        document.querySelector('.main--image.image--pc').style.display = 'block';
        document.querySelector('.main--image.image--sp').style.display = 'none';
    }
}



/**
 * data/datatimeタイプのinput用の文字列を返します
 * 
 * @param {Date} targetDate 日付オブジェクト
 * @param {Boolean} isIncludeTime true = datatime / false = data
 * @returns String input[type=date/datetime] 用の日付形式の文字列
 */
function dateString(targetDate,isIncludeTime) {
    let year = targetDate.getFullYear();
    let month = ('0' + (targetDate.getMonth() + 1)).slice(-2);
    let date = ('0' + targetDate.getDate()).slice(-2);
    let result = year + '-' + month + '-' + date;

    if (isIncludeTime) {
        let hours = ('0' + targetDate.getHours()).slice(-2);
        let minutes = ('0' + targetDate.getMinutes()).slice(-2);
        result += 'T' + hours + ':' + minutes;
    }

    return result;
}



/**
 * ランダムなコードを生成します
 */
function code() {
    const character = ["A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "j", "k", "m", "n", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "+", "-", "~", "@", "*", "="];
    const random = Math.floor(Math.random()*(16 - 6) + 6);
    let codeStrings = "";
    for (let index = 0; index < random; index++) {
        const n = Math.floor(Math.random()*(60 - 0) + 0);
        codeStrings += character[n];
        console.log(n)
    }
    return codeStrings;
}