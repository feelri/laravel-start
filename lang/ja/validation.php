<?php

return [
    'accepted'             => ':attribute フィールドは受け入れられなければなりません。',
    'accepted_if'          => ':other が :value の場合、:attribute フィールドは受け入れられなければなりません。',
    'active_url'           => ':attribute フィールドは有効な URL でなければなりません。',
    'after'                => ':attribute フィールドは :date 以降の日付でなければなりません。',
    'after_or_equal'       => ':attribute フィールドは :date 以降または :date と同じ日付でなければなりません。',
    'alpha'                => ':attribute フィールドは文字のみで構成されなければなりません。',
    'alpha_dash'           => ':attribute フィールドは文字、数字、ダッシュ、およびアンダースコアのみで構成されなければなりません。',
    'alpha_num'            => ':attribute フィールドは文字と数字のみで構成されなければなりません。',
    'array'                => ':attribute フィールドは配列でなければなりません。',
    'ascii'                => ':attribute フィールドは単バイトのアルファベット数字文字と記号のみで構成されなければなりません。',
    'before'               => ':attribute フィールドは :date 以前の日付でなければなりません。',
    'before_or_equal'      => ':attribute フィールドは :date 以前または :date と同じ日付でなければなりません。',
    'between'              => [
        'array'   => ':attribute フィールドは :min から :max 個の項目でなければなりません。',
        'file'    => ':attribute フィールドは :min から :max バイトの間でなければなりません。',
        'numeric' => ':attribute フィールドは :min から :max の間でなければなりません。',
        'string'  => ':attribute フィールドは :min から :max 文字の間でなければなりません。',
    ],
    'boolean'              => ':attribute フィールドは true または false でなければなりません。',
    'can'                  => ':attribute フィールドには権限のない値が含まれています。',
    'confirmed'            => ':attribute フィールドの確認が一致していません。',
    'contains'             => ':attribute フィールドには必須の値が含まれていません。',
    'current_password'     => 'パスワードが正しくありません。',
    'date'                 => ':attribute フィールドは有効な日付でなければなりません。',
    'date_equals'          => ':attribute フィールドは :date と同じ日付でなければなりません。',
    'date_format'          => ':attribute フィールドは :format フォーマットと一致する必要があります。',
    'decimal'              => ':attribute フィールドは :decimal 桁の小数でなければなりません。',
    'declined'             => ':attribute フィールドは拒否されなければなりません。',
    'declined_if'          => ':other が :value の場合、:attribute フィールドは拒否されなければなりません。',
    'different'            => ':attribute フィールドと :other は異なる必要があります。',
    'digits'               => ':attribute フィールドは :digits 桁の数字でなければなりません。',
    'digits_between'       => ':attribute フィールドは :min から :max 桁の数字の間でなければなりません。',
    'dimensions'           => ':attribute フィールドの画像サイズが無効です。',
    'distinct'             => ':attribute フィールドには重複した値があります。',
    'doesnt_end_with'      => ':attribute フィールドは次のいずれかで終わってはなりません: :values。',
    'doesnt_start_with'    => ':attribute フィールドは次のいずれかで始まってはなりません: :values。',
    'email'                => ':attribute フィールドは有効な電子メールアドレスでなければなりません。',
    'ends_with'            => ':attribute フィールドは次のいずれかで終わらなければなりません: :values。',
    'enum'                 => '選択された :attribute は無効です。',
    'exists'               => '選択された :attribute は無効です。',
    'extensions'           => ':attribute フィールドは次の拡張子のいずれかでなければなりません: :values。',
    'file'                 => ':attribute フィールドはファイルでなければなりません。',
    'filled'               => ':attribute フィールドには値が必要です。',
    'gt'                   => [
        'array'   => ':attribute フィールドには :value より多くの項目が必要です。',
        'file'    => ':attribute フィールドは :value キロバイトよりも大きくなければなりません。',
        'numeric' => ':attribute フィールドは :value より大きくなければなりません。',
        'string'  => ':attribute フィールドは :value 文字よりも大きくなければなりません。',
    ],
    'gte'                  => [
        'array'   => ':attribute フィールドには :value 個以上の項目が必要です。',
        'file'    => ':attribute フィールドは :value キロバイト以上でなければなりません。',
        'numeric' => ':attribute フィールドは :value 以上でなければなりません。',
        'string'  => ':attribute フィールドは :value 文字以上でなければなりません。',
    ],
    'hex_color'            => ':attribute フィールドは有効な 16 進数の色でなければなりません。',
    'image'                => ':attribute フィールドは画像でなければなりません。',
    'in'                   => '選択された :attribute は無効です。',
    'in_array'             => ':attribute フィールドは :other に存在する必要があります。',
    'integer'              => ':attribute フィールドは整数でなければなりません。',
    'ip'                   => ':attribute フィールドは有効な IP アドレスでなければなりません。',
    'ipv4'                 => ':attribute フィールドは有効な IPv4 アドレスでなければなりません。',
    'ipv6'                 => ':attribute フィールドは有効な IPv6 アドレスでなければなりません。',
    'json'                 => ':attribute フィールドは有効な JSON 文字列でなければなりません。',
    'list'                 => ':attribute フィールドはリストでなければなりません。',
    'lowercase'            => ':attribute フィールドは小文字でなければなりません。',
    'lt'                   => [
        'array'   => ':attribute フィールドには :value より少ない項目が必要です。',
        'file'    => ':attribute フィールドは :value キロバイトよりも小さくなければなりません。',
        'numeric' => ':attribute フィールドは :value より小さくなければなりません。',
        'string'  => ':attribute フィールドは :value 文字よりも小さくなければなりません。',
    ],
    'lte'                  => [
        'array'   => ':attribute フィールドには :value より多くの項目を持つことはできません。',
        'file'    => ':attribute フィールドは :value キロバイト以下でなければなりません。',
        'numeric' => ':attribute フィールドは :value 以下でなければなりません。',
        'string'  => ':attribute フィールドは :value 文字以下でなければなりません。',
    ],
    'mac_address'          => ':attribute フィールドは有効な MAC アドレスでなければなりません。',
    'max'                  => [
        'array'   => ':attribute フィールドには :max より多くの項目を持つことはできません。',
        'file'    => ':attribute フィールドは :max キロバイトよりも大きくすることはできません。',
        'numeric' => ':attribute フィールドは :max より大きくすることはできません。',
        'string'  => ':attribute フィールドは :max 文字よりも大きくすることはできません。',
    ],
    'max_digits'           => ':attribute フィールドには :max より多くの桁を持つことはできません。',
    'mimes'                => ':attribute フィールドは :values のタイプのファイルでなければなりません。',
    'mimetypes'            => ':attribute フィールドは :values のタイプのファイルでなければなりません。',
    'min'                  => [
        'array'   => ':attribute フィールドには少なくとも :min 個の項目が必要です。',
        'file'    => ':attribute フィールドは少なくとも :min キロバイトでなければなりません。',
        'numeric' => ':attribute フィールドは少なくとも :min でなければなりません。',
        'string'  => ':attribute フィールドは少なくとも :min 文字でなければなりません。',
    ],
    'min_digits'           => ':attribute フィールドには少なくとも :min 桁が必要です。',
    'missing'              => ':attribute フィールドは欠落している必要があります。',
    'missing_if'           => ':other が :value の場合、:attribute フィールドは欠落している必要があります。',
    'missing_unless'       => ':other が :values にない場合、:attribute フィールドは欠落している必要があります。',
    'missing_with'         => ':values が存在する場合、:attribute フィールドは欠落している必要があります。',
    'missing_with_all'     => ':values がすべて存在する場合、:attribute フィールドは欠落している必要があります。',
    'multiple_of'          => ':attribute フィールドは :value の倍数でなければなりません。',
    'not_in'               => '選択された :attribute は無効です。',
    'not_regex'            => ':attribute フィールドの形式が無効です。',
    'numeric'              => ':attribute フィールドは数字でなければなりません。',
    'password'             => [
        'letters'       => ':attribute フィールドには少なくとも 1 つの文字が必要です。',
        'mixed'         => ':attribute フィールドには少なくとも 1 つの大文字と 1 つの小文字が必要です。',
        'numbers'       => ':attribute フィールドには少なくとも 1 つの数字が必要です。',
        'symbols'       => ':attribute フィールドには少なくとも 1 つの記号が必要です。',
        'uncompromised' => '指定された :attribute はデータリークで発見されました。別の :attribute を選択してください。',
    ],
    'present'              => ':attribute フィールドは存在する必要があります。',
    'present_if'           => ':other が :value の場合、:attribute フィールドは存在する必要があります。',
    'present_unless'       => ':other が :value でない場合、:attribute フィールドは存在する必要があります。',
    'present_with'         => ':values が存在する場合、:attribute フィールドは存在する必要があります。',
    'present_with_all'     => ':values がすべて存在する場合、:attribute フィールドは存在する必要があります。',
    'prohibited'           => ':attribute フィールドは禁止されています。',
    'prohibited_if'        => ':other が :value の場合、:attribute フィールドは禁止されています。',
    'prohibited_unless'    => ':other が :values にない場合、:attribute フィールドは禁止されています。',
    'prohibits'            => ':attribute フィールドは :other の存在を禁止しています。',
    'regex'                => ':attribute フィールドの形式が無効です。',
    'required'             => ':attribute フィールドは必須です。',
    'required_array_keys'  => ':attribute フィールドには次のエントリが必要です: :values。',
    'required_if'          => ':other が :value の場合、:attribute フィールドは必須です。',
    'required_if_accepted' => ':other が受け入れられた場合、:attribute フィールドは必須です。',
    'required_if_declined' => ':other が拒否された場合、:attribute フィールドは必須です。',
    'required_unless'      => ':other が :values にない場合、:attribute フィールドは必須です。',
    'required_with'        => ':values が存在する場合、:attribute フィールドは必須です。',
    'required_with_all'    => ':values がすべて存在する場合、:attribute フィールドは必須です。',
    'required_without'     => ':values が存在しない場合、:attribute フィールドは必須です。',
    'required_without_all' => ':values がすべて存在しない場合、:attribute フィールドは必須です。',
    'same'                 => ':attribute フィールドは :other と一致する必要があります。',
    'size'                 => [
        'array'   => ':attribute フィールドには :size 個の項目が必要です。',
        'file'    => ':attribute フィールドは :size キロバイトでなければなりません。',
        'numeric' => ':attribute フィールドは :size でなければなりません。',
        'string'  => ':attribute フィールドは :size 文字でなければなりません。',
    ],
    'starts_with'          => ':attribute フィールドは次のいずれかで始まらなければなりません: :values。',
    'string'               => ':attribute フィールドは文字列でなければなりません。',
    'timezone'             => ':attribute フィールドは有効なタイムゾーンでなければなりません。',
    'unique'               => ':attribute は既に使用されています。',
    'uploaded'             => ':attribute のアップロードに失敗しました。',
    'uppercase'            => ':attribute フィールドは大文字でなければなりません。',
    'url'                  => ':attribute フィールドは有効な URL でなければなりません。',
    'ulid'                 => ':attribute フィールドは有効な ULID でなければなりません。',
    'uuid'                 => ':attribute フィールドは有効な UUID でなければなりません。',
    'verify'               => ':attribute の検証に失敗しました',
    'bank_account'         => ':attribute の形式が正しくありません',
    'id_card'              => ':attribute の形式が正しくありません',
    'mobile'               => ':attribute の形式が正しくありません',
    'attributes'           => [
        'account'        => 'アカウント',
        'email'          => 'メールアドレス',
        'mobile'         => '携帯電話番号',
        'password'       => 'パスワード',
        'old_password'   => '古いパスワード',
        'new_password'   => '新しいパスワード',
        'verify_token'   => '人間確認',
        'name'           => '名前',
        'nickname'       => 'ニックネーム',
        'gender'         => '性別',
        'avatar'         => 'アバター',
        'role'           => 'ロール',
        'is_disable'     => '無効化',
        'description'    => '説明',
        'rank'           => 'ランク',
        'type'           => 'タイプ',
        'permission_ids' => '権限グループ',
        'icon'           => 'アイコン',
        'uri'            => 'ルート',
        'method'         => 'リクエスト方法',
        'component'      => 'コンポーネントパス',
        'is_show'        => '表示',
        'verify_code'    => '確認コード',
        'sms_code'       => 'SMS コード',
        'parent_id'      => '親 ID',
        'file'           => 'ファイル'
    ],
];