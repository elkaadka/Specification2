<?php declare(strict_types=1);

namespace Kanel\Specification2;

use Kanel\Specification2\Exceptions\WrongUsageException;

class Specification implements SpecificationInterface
{
    protected $result;

    /**
     * checks if a specification is valid
     * @param SpecificationInterface $specification
     * @return Specification
     * @throws WrongUsageException
     */
    final public function isSatisfiedBy(SpecificationInterface $specification): Specification
    {
        if ( isset($this->result)) {
            throw new WrongUsageException('isSatisfiedBy can not be called after the operators');
        }

        $this->result = $specification->isValid();

        return $this;
    }

    /**
     * checks if all the specifications sent as the function's parameters are all valid
     * @param SpecificationInterface[] ...$specifications
     * @return Specification
     * @throws WrongUsageException
     */
    final public function isSatisfiedByAll(SpecificationInterface...$specifications): Specification
    {
        if ( isset($this->result)) {
            throw new WrongUsageException('isSatisfiedByAll can not be called after the operators');
        }

        $this->result = true;

        foreach ($specifications as $specification) {
            $this->result = ($this->result && $specification->isValid());
        }

        return $this;
    }

    /**
     * checks if one of all the specifications sent as the function's parameters is valid
     * @param SpecificationInterface[] ...$specifications
     * @return Specification
     * @throws WrongUsageException
     */
    final public function isSatisfiedByAny(SpecificationInterface...$specifications): Specification
    {
        if ( isset($this->result)) {
            throw new WrongUsageException('isSatisfiedByAll can not be called after the operators');
        }

        $this->result = false;

        foreach ($specifications as $specification) {
            $this->result = ($this->result || $specification->isValid());
        }

        return $this;
    }

    /**
     * Adds an 'and' condition to the specification
     * @param SpecificationInterface $specification
     * @return Specification
     * @throws WrongUsageException
     */
    final public function and(SpecificationInterface $specification): Specification
    {
        if (! isset($this->result)) {
            throw new WrongUsageException('and operator can not be applied first');
        }

        $this->result = ($this->result && $specification->isValid());

        return $this;
    }

    /**
     * Adds an 'or' condition to the specification
     * @param SpecificationInterface $specification
     * @return Specification
     * @throws WrongUsageException
     */
    final public function or(SpecificationInterface $specification): Specification
    {
        if (! isset($this->result)) {
            throw new WrongUsageException('or operator can not be applied first');
        }

        $this->result = ($this->result || $specification->isValid());

        return $this;
    }

    /**
     * Adds a 'xor' condition to the specification
     * @param SpecificationInterface $specification
     * @return Specification
     * @throws WrongUsageException
     */
    final public function xor(SpecificationInterface $specification): Specification
    {
        if (! isset($this->result)) {
            throw new WrongUsageException('xor operator can not be applied first');
        }

        $this->result = ($this->result xor $specification->isValid());

        return $this;
    }

    /**
     * Adds an 'and !' condition to the specification
     * @param SpecificationInterface $specification
     * @return Specification
     * @throws WrongUsageException
     */
    final public function andNot(SpecificationInterface $specification): Specification
    {
        if (! isset($this->result)) {
            throw new WrongUsageException('xor operator can not be applied first');
        }

        $this->result = ($this->result && ! $specification->isValid());

        return $this;
    }

    /**
     * Adds an 'or !' condition to the specification
     * @param SpecificationInterface $specification
     * @return Specification
     * @throws WrongUsageException
     */
    final public function orNot(SpecificationInterface $specification): Specification
    {
        if (! isset($this->result)) {
            throw new WrongUsageException('xor operator can not be applied first');
        }

        $this->result = ($this->result || ! $specification->isValid());

        return $this;
    }

    /**
     * Adds an 'xor !' condition to the specification
     * @param SpecificationInterface $specification
     * @return Specification
     * @throws WrongUsageException
     */
    final public function xorNot(SpecificationInterface $specification): Specification
    {
        if (! isset($this->result)) {
            throw new WrongUsageException('xor operator can not be applied first');
        }

        $this->result = ($this->result xor ! $specification->isValid());

        return $this;
    }

    /**
     * checks if the all specifications chaining is valid, gives the final result
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->result ?? false;
    }
}