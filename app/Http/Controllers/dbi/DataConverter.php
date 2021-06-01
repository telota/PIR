<?php

namespace App\Http\Controllers\dbi;

use Response;

class DataConverter {

    public function txt ($data) {

        $data = $this->removeTustepComments($data[0]);
        $return = [
            'ID:'."\n".$data['id'],
            'publisher:'."\n".'https://pir.bbaw.de'."\n".'Prosopographia Imperii Romani, Berlin-Brandenburg Academy of Sciences and Humanities, Jägerstraße 22/23, 10117 Berlin, Germany',
            'license:'."\n".'https://creativecommons.org/licenses/by/4.0'."\n".'CC BY 4.0'
        ];

        foreach ($data as $key => $val) {
            if (!in_array($key, ['string', 'class', 'id']) && !empty($val)) {
                if ($key === 'annotated') $key = 'name';
                if ($key === 'updated_at') $key = 'modified';
                $return[] = $key.":\n".$val;
            }
        }

        return implode("\n\n", $return);
    }

    public function json ($data) {

        $data = $this->removeTustepComments($data[0]);
        $return = [
            'id' => $data['id'],
            'publisher' => [
                'id' => 'https://pir.bbaw.de',
                'value' => 'Prosopographia Imperii Romani, Berlin-Brandenburg Academy of Sciences and Humanities, Jägerstraße 22/23, 10117 Berlin, Germany'
            ],
            'license' => [
                'id' => 'https://creativecommons.org/licenses/by/4.0',
                'value' => 'CC BY 4.0'
            ]
        ];

        foreach ($data as $key => $val) {
            if (!in_array($key, ['string', 'class', 'id']) && !empty($val)) {
                if ($key === 'annotated') $key = 'name';
                if ($key === 'updated_at') $key = 'modified';

                $return[$key] = $val;
            }
        }

        return Response::json($return);
    }

    public function jsonld ($data) {

        $context = [
            'dcterms'   => 'https://www.dublincore.org/specifications/dublin-core/dcmi-terms/terms/',
            //'gender'    => 'https://d-nb.info/standards/vocab/gnd/gender.html#',
            'skos'      => 'https://www.w3.org/2009/08/skos-reference/skos.html#',
        ];

        $data = $this->removeTustepComments($data[0]);
        $graph['@id'] = $data['id'];

        //$graph['@type'] = $entity === 'coins' ? 'http://nomisma.org/ontology#NumismaticObject' : 'http://nomisma.org/ontology#TypeSeriesItem';

        $data['id'] = explode('/', $data['id']);
        $graph['dcterms:title'][0]['@value'] = 'PIR ID '.intval(end($data['id']));

        // Publisher
        $graph['dcterms:publisher'][0] = [
            '@id' => 'https://pir.bbaw.de',
            '@value' => 'Prosopographia Imperii Romani, Berlin-Brandenburg Academy of Sciences and Humanities, Jägerstraße 22/23, 10117 Berlin, Germany'
        ];

        // License
        $graph['dcterms:license'][0] = [
            '@id' => 'https://creativecommons.org/licenses/by/4.0',
            '@value' => 'CC BY 4.0'
        ];

        // Gender
        if (strtolower(substr($data['gender'], 0, 1)) === 'f') $data['gender'] = ['@id' => 'https://d-nb.info/standards/vocab/gnd/gender.html#female', '@value' => 'female'];
        else if (strtolower(substr($data['gender'], 0, 1)) === 'm') $data['gender'] = ['@id' => 'https://d-nb.info/standards/vocab/gnd/gender.html#male', '@value' => 'male'];
        else $data['gender'] = ['@id' => 'https://d-nb.info/standards/vocab/gnd/gender.html#notKnown', '@value' => 'not known'];
        $graph['gender'][0] = $data['gender'];

        // References
        $isBR = 'https://www.dublincore.org/specifications/dublin-core/dcmi-terms/terms/BibliographicResource';
        if (!empty($data['reference'])) $graph['dcterms:references'][0] = ['@value' => $data['reference'], '@type' => $isBR];
        foreach (['sources', 'literature'] as $key) {
            if (!empty($data[$key])) {
                $split = explode('));', $data[$key]);
                foreach($split as $i => $item) {
                    $graph['dcterms:references'][] = ['@value' => trim($item.($i + 1 < count($split) ? '))' : '')), '@type' => $isBR];
                }
            }
        }

        // Article and Notes
        foreach (['article', 'notes'] as $key) {
            if (!empty($data[$key])) $graph['skos:note'][] = ['@value' => $data[$key]];
        }

        // last Update
        $graph['dcterms:modified'][0] = ['@value' => $data['updated_at']];


        return Response::json([
            '@context' => $context,
            '@graph' => $graph
        ], 200);
    }

    public function removeTustepComments ($data) {
        foreach ($data as $key => $val) {
            $val = preg_replace('/{{(.*?)}}/', ' ', $val);
            $item[$key] = trim(preg_replace('/\s+/', ' ', $val));
        }
        return $item;
    }
}
