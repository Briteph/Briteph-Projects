<?php

namespace PrestoPlayer\Pro\Services;

class EmailExport
{
    /**
     * Holds the current step
     *
     * @var integer
     */
    public $step = 1;

    /**
     * Filetype
     *
     * @var string
     */
    public $filetype = '.csv';

    /**
     * Holds the filename
     *
     * @var string
     */
    public $filename = '';

    /**
     * Holds the file url
     *
     * @var string
     */
    public $file = '';

    /**
     * Is the file writable
     *
     * @var boolean
     */
    public $is_writable = false;

    /**
     * Is this done
     *
     * @var boolean
     */
    public $done = false;

    /**
     * How many rows per step
     *
     * @var integer
     */
    public $per_step = 50;

    /**
     * Register download hook
     *
     * @return void
     */
    public function register()
    {
        add_action('init', [$this, 'maybeDownloadFile']);
    }

    /**
     * Initialize Data
     *
     * @param integer $step Which step we are on
     */
    public function __construct($step = 1)
    {
        // temp dir and file path
        $temp_dir = get_temp_dir();
        $this->filetype = '.csv';
        $this->filename = 'presto-player-emails' . $this->filetype;
        $this->file = trailingslashit($temp_dir) . $this->filename;
        $this->step = $step;
        $this->is_writable = is_writeable($temp_dir);
    }

    /**
     * Maybe download the export
     * Deletes the file when downloaded to prevent leaks
     *
     * @return void
     */
    public function maybeDownloadFile()
    {
        // only for our action
        if (empty($_GET['presto_action']) || 'download_export' !== $_GET['presto_action']) {
            return;
        }

        // nonce
        if (empty($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'presto_download_export')) {
            wp_die('Cheatin, huh?');
        }

        // permissions check
        if (!current_user_can('manage_options')) {
            return;
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"presto-player-email.csv\";");
        header("Content-Transfer-Encoding: binary");

        echo $this->getFile();

        if (file_exists($this->file)) {
            unlink($this->file);
        }

        exit;
    }

    /**
     * Process export step
     *
     * @return boolean If there are more rows
     */
    public function processStep()
    {
        // start with columns on step 1
        if ($this->step < 2) {
            if (file_exists($this->file)) {
                unlink($this->file);
            }
            $this->printColumns();
        }

        $rows = $this->printRows();

        return (bool) $rows;
    }

    /**
     * Column names
     *
     * @return array
     */
    public function getColumns()
    {
        return [
            'Email Address',
            'First Name',
            'Last Name',
            'Preset ID',
            'Video ID'
        ];
    }

    /**
     * Print columns for CSV
     *
     * @return array
     */
    public function printColumns()
    {
        $cols = $this->getColumns();

        $data = '';

        for ($i = 0; $i < count($cols); $i++) {
            $data .= $cols[$i];
            // We don't need an extra space after the first column
            if ($i == 0) {
                $data .= ',';
                continue;
            }
            if ($i == (count($cols) - 1)) {
                $data .= "\r\n";
            } else {
                $data .= ",";
            }
        }

        $this->stashStepData($data);

        return $data;
    }

    /**
     * Print rows of data
     *
     * @return boolean
     */
    public function printRows()
    {
        $row_data = '';
        $data = $this->getData();
        $cols = $this->getColumns();

        if ($data) {
            // Output each row
            foreach ($data as $row) {
                $i = 1;
                foreach ($row as $col_id => $column) {
                    // Make sure the column is valid
                    if (in_array($col_id, $cols)) {
                        $row_data .= '"' . addslashes(preg_replace("/\"/", "'", $column)) . '"';
                        $row_data .= $i == count($cols) ? '' : ',';
                        $i++;
                    }
                }
                $row_data .= "\r\n";
            }

            $this->stashStepData($row_data);

            return $row_data;
        }

        return false;
    }

    /**
     * Retrieve the file data is written to
     *
     * @since 2.4
     * @return string
     */
    protected function getFile()
    {
        $file = '';

        if (@file_exists($this->file)) {
            if (!is_writeable($this->file)) {
                $this->is_writable = false;
            }
            $file = @file_get_contents($this->file);
        } else {
            @file_put_contents($this->file, '');
            @chmod($this->file, 0664);
        }

        return $file;
    }

    /**
     * Write step data
     *
     * @param string $data
     * @return void
     */
    public function stashStepData($data = '')
    {
        $file = $this->getFile();
        $file .= $data;
        @file_put_contents($this->file, $file);

        // If we have no rows after this step, mark it as an empty export
        $file_rows    = file($this->file, FILE_SKIP_EMPTY_LINES);
        $default_cols = $this->getColumns();
        $default_cols = empty($default_cols) ? 0 : 1;

        $this->is_empty = count($file_rows) == $default_cols ? true : false;
    }

    /**
     * Get subscriber data
     *
     * @return array Array of row data for CSV
     */
    public function getData()
    {
        $data = [];

        $subscriber_ids = get_posts([
            'post_type' => 'pp_email_submission',
            'posts_per_page' => $this->per_step,
            'offset' => $this->per_step * ($this->step - 1),
            'fields' => 'ids'
        ]);

        foreach ($subscriber_ids as $subscriber_id) {
            $data[] = [
                'Email Address' => get_post_meta($subscriber_id, 'email', true),
                'First Name'     => get_post_meta($subscriber_id, 'firstname', true),
                'Last Name'       => get_post_meta($subscriber_id, 'lastname', true),
                'Preset ID' => get_post_meta($subscriber_id, 'preset_id', true),
                'Video ID'     => get_post_meta($subscriber_id, 'video_id', true)
            ];
        }

        return $data;
    }

    /**
     * Calculate percent complete
     *
     * @return integer
     */
    public function getPercentageComplete()
    {
        $total = wp_count_posts('pp_email_submission')->publish;
        return min((($this->per_step * $this->step) / $total) * 100, 100);
    }
}
