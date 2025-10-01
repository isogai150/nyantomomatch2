<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attributeを承認する必要があります。',
    'accepted_if' => ':otherが:valueの場合、:attributeを承認する必要があります。',
    'active_url' => ':attributeは、有効なURLではありません。',
    'after' => ':attributeは:dateより後の日付である必要があります。',
    'after_or_equal' => ':attributeは:date以降の日付である必要があります。',
    'alpha' => ':attributeは英字のみで構成されている必要があります。',
    'alpha_dash' => ':attributeは英字、数字、ダッシュ、アンダースコアのみで構成されている必要があります。',
    'alpha_num' => ':attributeは英字と数字のみで構成されている必要があります。',
    'array' => ':attributeは配列である必要があります。',
    'ascii' => ':attributeは1バイトの英数字と記号のみで構成されている必要があります。',
    'before' => ':attributeは:dateより前の日付である必要があります。',
    'before_or_equal' => ':attributeは:date以前の日付である必要があります。',
    'between' => [
        'array' => ':attributeは:min個から:max個の間で項目を持っている必要があります。',
        'file' => ':attributeは:minキロバイトから:maxキロバイトの間である必要があります。',
        'numeric' => ':attributeは:minから:maxの間である必要があります。',
        'string' => ':attributeは:min文字から:max文字の間である必要があります。',
    ],
    'boolean' => ':attributeフィールドは、trueかfalseである必要があります。',
    'confirmed' => ':attributeの確認が一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attributeは有効な日付ではありません。',
    'date_equals' => ':attributeは:dateと等しい日付である必要があります。',
    'date_format' => ':attributeは:format形式と一致しません。',
    'decimal' => ':attributeは小数点以下:decimal桁である必要があります。',
    'declined' => ':attributeを辞退する必要があります。',
    'declined_if' => ':otherが:valueの場合、:attributeを辞退する必要があります。',
    'different' => ':attributeと:otherは異なっている必要があります。',
    'digits' => ':attributeは:digits桁である必要があります。',
    'digits_between' => ':attributeは:min桁から:max桁の間である必要があります。',
    'dimensions' => ':attributeは画像として無効な寸法です。',
    'distinct' => ':attributeフィールドは重複した値を持っています。',
    'doesnt_end_with' => ':attributeは、次の値のいずれかで終わってはなりません: :values。',
    'doesnt_start_with' => ':attributeは、次の値のいずれかで始まってはなりません: :values。',
    'email' => ':attributeは有効なメールアドレスである必要があります。',
    'ends_with' => ':attributeは、次の値のいずれかで終わる必要があります: :values。',
    'enum' => '選択された:attributeは無効です。',
    'exists' => '選択された:attributeは無効です。',
    'file' => ':attributeはファイルである必要があります。',
    'filled' => ':attributeフィールドは値を持っている必要があります。',
    'gt' => [
        'array' => ':attributeは:value個より多くの項目を持っている必要があります。',
        'file' => ':attributeは:valueキロバイトより大きい必要があります。',
        'numeric' => ':attributeは:valueより大きい必要があります。',
        'string' => ':attributeは:value文字より多い必要があります。',
    ],
    'gte' => [
        'array' => ':attributeは:value個以上の項目を持っている必要があります。',
        'file' => ':attributeは:valueキロバイト以上である必要があります。',
        'numeric' => ':attributeは:value以上である必要があります。',
        'string' => ':attributeは:value文字以上である必要があります。',
    ],
    'image' => ':attributeは画像である必要があります。',
    'in' => '選択された:attributeは無効です。',
    'in_array' => ':attributeフィールドは:otherに存在しません。',
    'integer' => ':attributeは整数である必要があります。',
    'ip' => ':attributeは有効なIPアドレスである必要があります。',
    'ipv4' => ':attributeは有効なIPv4アドレスである必要があります。',
    'ipv6' => ':attributeは有効なIPv6アドレスである必要があります。',
    'json' => ':attributeは有効なJSON文字列である必要があります。',
    'lowercase' => ':attributeは小文字である必要があります。',
    'lt' => [
        'array' => ':attributeは:value個より少ない項目を持っている必要があります。',
        'file' => ':attributeは:valueキロバイトより小さい必要があります。',
        'numeric' => ':attributeは:valueより小さい必要があります。',
        'string' => ':attributeは:value文字より少ない必要があります。',
    ],
    'lte' => [
        'array' => ':attributeは:value個以下の項目を持っている必要があります。',
        'file' => ':attributeは:valueキロバイト以下である必要があります。',
        'numeric' => ':attributeは:value以下である必要があります。',
        'string' => ':attributeは:value文字以下である必要があります。',
    ],
    'mac_address' => ':attributeは有効なMACアドレスである必要があります。',
    'max' => [
        'array' => ':attributeは:max個より多くの項目を持ってはいけません。',
        'file' => ':attributeは:maxキロバイトを超えてはいけません。',
        'numeric' => ':attributeは:maxを超えてはいけません。',
        'string' => ':attributeは:max文字を超えてはいけません。',
    ],
    'max_digits' => ':attributeは:max桁以下である必要があります。',
    'mimes' => ':attributeは:valuesタイプのファイルである必要があります。',
    'mimetypes' => ':attributeは:valuesタイプのファイルである必要があります。',
    'min' => [
        'array' => ':attributeは少なくとも:min個の項目を持っている必要があります。',
        'file' => ':attributeは少なくとも:minキロバイトである必要があります。',
        'numeric' => ':attributeは少なくとも:minである必要があります。',
        'string' => ':attributeは少なくとも:min文字である必要があります。',
    ],
    'min_digits' => ':attributeは少なくとも:min桁である必要があります。',
    'missing' => ':attributeフィールドは存在してはいけません。',
    'missing_if' => ':otherが:valueの場合、:attributeフィールドは存在してはいけません。',
    'missing_unless' => ':otherが:valueでない限り、:attributeフィールドは存在してはいけません。',
    'missing_with' => ':valuesが存在する場合、:attributeフィールドは存在してはいけません。',
    'missing_with_all' => ':valuesがすべて存在する場合、:attributeフィールドは存在してはいけません。',
    'multiple_of' => ':attributeは:valueの倍数である必要があります。',
    'not_in' => '選択された:attributeは無効です。',
    'not_regex' => ':attributeの形式が無効です。',
    'numeric' => ':attributeは数値である必要があります。',
    'password' => [
        'letters' => ':attributeは少なくとも1文字のアルファベットを含む必要があります。',
        'mixed' => ':attributeは少なくとも1文字の大文字と1文字の小文字を含む必要があります。',
        'numbers' => ':attributeは少なくとも1つの数字を含む必要があります。',
        'symbols' => ':attributeは少なくとも1つの記号を含む必要があります。',
        'uncompromised' => '指定された:attributeはデータ漏洩で確認されています。別の:attributeを選択してください。',
    ],
    'present' => ':attributeフィールドは存在する必要があります。',
    'prohibited' => ':attributeフィールドは禁止されています。',
    'prohibited_if' => ':otherが:valueの場合、:attributeフィールドは禁止されています。',
    'prohibited_unless' => ':otherが:valuesにない限り、:attributeフィールドは禁止されています。',
    'prohibits' => ':attributeフィールドは、:otherの存在を禁止します。',
    'regex' => ':attributeの形式が無効です。',
    'required' => ':attributeフィールドは必須です。',
    'required_array_keys' => ':attributeフィールドには、:valuesのエントリが含まれている必要があります。',
    'required_if' => ':otherが:valueの場合、:attributeフィールドは必須です。',
    'required_if_accepted' => ':otherが承認された場合、:attributeフィールドは必須です。',
    'required_unless' => ':otherが:valuesにない限り、:attributeフィールドは必須です。',
    'required_with' => ':valuesが存在する場合、:attributeフィールドは必須です。',
    'required_with_all' => ':valuesがすべて存在する場合、:attributeフィールドは必須です。',
    'required_without' => ':valuesが存在しない場合、:attributeフィールドは必須です。',
    'required_without_all' => ':valuesがすべて存在しない場合、:attributeフィールドは必須です。',
    'same' => ':attributeと:otherは一致している必要があります。',
    'size' => [
        'array' => ':attributeは:size個の項目を含む必要があります。',
        'file' => ':attributeは:sizeキロバイトである必要があります。',
        'numeric' => ':attributeは:sizeである必要があります。',
        'string' => ':attributeは:size文字である必要があります。',
    ],
    'starts_with' => ':attributeは、次の値のいずれかで始まる必要があります: :values。',
    'string' => ':attributeは文字列である必要があります。',
    'timezone' => ':attributeは有効なタイムゾーンである必要があります。',
    'unique' => ':attributeは既に使われています。',
    'uploaded' => ':attributeのアップロードに失敗しました。',
    'uppercase' => ':attributeは大文字である必要があります。',
    'url' => ':attributeは有効なURLである必要があります。',
    'ulid' => ':attributeは有効なULIDである必要があります。',
    'uuid' => ':attributeは有効なUUIDである必要があります。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'カスタムメッセージ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // 'email' => 'メールアドレス',
        // 'password' => 'パスワード',
        // など、属性名 (プレースホルダー) の日本語名を追加できます
    ],

];
