Ext.define( 'AppAnest.model.person.NaturalPerson', {
    extend: 'AppAnest.model.person.Person',

    classFields: [
        {
            name: 'type',
            type: 'auto',
            critical: true,
            defaultValue: 'N'
        }, {
            name: 'birthdate',
            type: 'auto'
        }, {
            name: 'sheetcode',
            type: 'auto'
        }, {
            name: 'gender',
            type: 'auto'
        }, {
            name: 'genderdescription',
            type: 'auto'
        }, {
            name: 'namemother',
            type: 'auto'
        }, {
            name: 'namefather',
            type: 'auto'
        }, {
            name: 'status',
            type: 'auto'
        }, {
            name: 'nationality',
            type: 'auto'
        }, {
            name: 'placebirth',
            type: 'auto'
        }, {
            name: 'admissiondate',
            type: 'auto'
        }, {
            name: 'voter',
            type: 'auto'
        }, {
            name: 'voterzone',
            type: 'auto'
        }, {
            name: 'votersection',
            type: 'auto'
        }, {
            name: 'voterissuingdate',
            type: 'auto'
        }, {
            name: 'pispasep',
            type: 'auto'
        }, {
            name: 'cpfnumber',
            type: 'auto'
        }, {
            name: 'identnumber',
            type: 'auto'
        }, {
            name: 'identissuing',
            type: 'auto'
        }, {
            name: 'identissuingdate',
            type: 'auto'
        }, {
            name: 'identissuingstate',
            type: 'auto'
        }, {
            name: 'crmnumber',
            type: 'auto'
        }, {
            name: 'racecolor',
            type: 'auto'
        }, {
            name: 'racecolordescription',
            type: 'auto'
        }, {
            name: 'registrationid',
            type: 'int'
        }, {
            name: 'registrationcode',
            type: 'int'
        }, {
            name: 'crmissuingstate',
            type: 'auto'
        }
    ]

});