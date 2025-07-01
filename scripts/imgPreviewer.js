class ImgPreviewer
{
    /**
     * コンストラクタ
     * 
     * @param {Array<String>}   allowedExtensions   許可するファイルのmimeタイプを格納した配列。
     * デフォルトで'image/jpeg', 'image/gif', 'image/png'の３つを指定
     * @param {Int}             maxSize             最大ファイルサイズをバイト単位で指定。
     * デフォルトは5000000(5MB)
     */
    constructor (
        allowedExtensions = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ],
        maxSize = 5000000)
    {
        this.allowedExtensions = allowedExtensions;
        this.maxSize = maxSize;
    }

    /**
     * プレビュー表示する
     * 
     * @param {Event} e イベントオブジェクト
     */
    setImgPreview(e)
    {
        const file = e.target.files[0];
        const validated = this.fileValidator(file);
        const previewElm = document.getElementById(e.target.dataset.targetId);

        this.resetPreview(previewElm);

        if (validated.is_cancel) {
            return false;
        }

        if (validated.errors) {
            alert(validated.errors);
            e.target.value = '';
            return false;
        }

        const fr = new FileReader();
        const classes = e.target.dataset.classes;

        fr.readAsDataURL(file);
 
        fr.addEventListener('load', {
            handleEvent: this.imgLoaded,
            classes: classes,
            previewElm: previewElm,
        });

    }

    /**
     * 画像要素の読み込み完了時に実行される。
     * imgタグを作成後、srcとclassをセットして、
     * プレビュー表示用要素の子要素に追加。
     * 
     * @param {Event} e イベントオブジェクト
     * 
     * @property {String} classes 付与するクラス
     * @property {DOM} プレビュー表示用のDOM要素
     */
    imgLoaded(e)
    {
        const imgElm = document.createElement('img');
        imgElm.src = e.target.result;

        // classが与えられているか確認して、与えられている場合はクラスを付与
        if (this.classes) {
            const classArray = this.classes.split(' ');
            for (let i = 0; i < classArray.length; i++) {
                imgElm.classList.add(classArray[i]);
            }
        }

        // imgタグを挿入
        this.previewElm.appendChild(imgElm);
    }

    /**
     * プレビューをリセットする関数（プレビュー用要素の子要素を全て削除する）
     * 
     * @param {DOM} previewElm プレビュー用のDOM要素 
     */
    resetPreview(previewElm)
    {
        if (previewElm.firstChild) {
            // 子要素がある場合は全削除
            while (previewElm.firstChild) {
                previewElm.removeChild(previewElm.firstChild);
            }
        }

    }

    /**
     * 選択されたファイルの種類（拡張子）、サイズを検証する
     * 
     * @param {File} file ファイルオブジェクト
     *  
     * @returns {Obj} 検証結果を格納したオブジェクト。
     * errorsプロパティにエラーがあった際のエラー文言を、
     * エラーがなかった場合は空文字を格納。
     */
    fileValidator(file)
    {
        // 返却値の作成
        const result = {
            is_cancel: false,
            errors: '',
        };

        if (!file) {
            result.is_cancel = true;
            return result;
        }

        // 拡張子検証
        if (!this.allowedExtensions.includes(file.type)) {
            result.status = false;
            result.errors += 'サポートされていない拡張子が選択されています。\n';
        }

        // ファイルサイズ検証
        if (file.size > this.maxSize) {
            result.status = false;
            result.errors += 'ファイルのサイズ上限を超えています。\n';
            result.errors += this.maxSize / 1000000;
            result.errors += 'MB以下のファイルを選択してください。';
        }

        return result;
    }
}